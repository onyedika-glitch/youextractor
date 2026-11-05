---
title: "Retries Done Right: When to Retry, How to Back Off, and How Not to Charge Someone Twice"
date: 2026-11-05
excerpt: A retry is the easiest fix in distributed systems and one of the easiest ways to make an outage worse. Here are the three questions to ask before every retry, how backoff and jitter really work, why idempotency keys matter, and how retrying an AI model is different.
reading_time: 13
image: /img/blog/retries-done-right/cover.png
image_alt: Hand-drawn sketch of a circular retry arrow, a stack of coins with a check mark, and a staircase of growing waits around the title Retries Done Right
---

A retry looks like the simplest fix in software. Something failed, so try again. One line of code, and a flaky network suddenly looks reliable.

But retries are also behind some of the worst failures I know of. A retry that charges a card twice. A retry that sends the same email five times. A retry storm that takes a slow service and finishes it off. In my last two articles, on [timeouts](/blog/timeouts-are-a-budget) and [rate limiting](/blog/rate-limiting-explained), retries kept showing up as the thing that turned a small problem into a big one.

So this one is about retries themselves. Before any retry, ask three questions:

1. **Should I retry this error at all?**
2. **Is it safe to do this twice?**
3. **When, and how many times?**

> **Short version:** only retry errors that are temporary (connection failures, timeouts, 429, 502, 503, 504). Make anything that changes data safe to repeat with an idempotency key. Wait with exponential backoff and full jitter, respect `Retry-After`, and cap attempts. Retry at one layer only, keep a retry budget, and stop calling a dependency that is clearly down. For queue jobs, assume every job can run twice. For AI models, a broken answer is a different kind of failure from a broken connection.

## Question 1: should I retry this error?

Errors fall into three groups. Getting this wrong is the most common retry bug.

![Three groups of errors: temporary ones like connection reset, 429, 502, 503 and 504 are worth retrying; permanent ones like 400, 401, 403, 404 and 422 will fail the same way every time; and ambiguous ones like a timeout after the request was sent, where the action may already have happened, are only safe to retry if the operation is idempotent.](/img/blog/retries-done-right/what-to-retry.webp)
*Retry the temporary, never the permanent, and be careful with the ambiguous.*

**Temporary errors: retry.** The request didn't work *this time*, but it might next time. A dropped connection, a DNS hiccup, `429 Too Many Requests`, `502 Bad Gateway`, `503 Service Unavailable`, `504 Gateway Timeout`. These are what retries are for.

**Permanent errors: don't retry.** `400 Bad Request`, `401 Unauthorized`, `403 Forbidden`, `404 Not Found`, `422 Unprocessable`. The request itself is wrong. Sending it again sends the same wrong request, just slower, and adds load for nothing. A retry rule like "retry on any exception" (`fn ($e) => true`) retries these too. I've written that line myself.

**Ambiguous errors: be careful.** This is the group people forget. If your request was sent and then the connection dropped or timed out *before the answer came back*, you don't know whether it worked. The server may have charged the card, created the record or sent the email, and only the reply got lost. Retrying is only safe if doing it twice has the same effect as doing it once. That's question 2.

## Question 2: is it safe to do this twice?

An operation is **idempotent** if doing it twice has the same result as doing it once.

- **Reading** is idempotent. Fetching a user twice changes nothing.
- **Setting** is usually idempotent. "Set the status to paid" twice still leaves it paid.
- **Adding** usually isn't. "Add 10 credits" twice gives 20. "Charge $20" twice charges $40. "Send the welcome email" twice sends two emails.

HTTP even has rules for this. `GET`, `PUT` and `DELETE` are defined as idempotent. `POST` is not. That's why many HTTP clients retry `GET` requests automatically but not `POST`.

The problem is that the operations you most want to retry, like payments, orders and messages, are exactly the ones that aren't idempotent. You have to make them safe.

### Idempotency keys

The standard fix is an **idempotency key**. The client creates a unique ID for the *operation* (not for each attempt), sends it with the request, and sends the *same* ID on every retry. The server remembers which keys it has already handled. If it sees a key again, it returns the original result instead of doing the work again.

![Idempotency key flow: the client sends a charge request with key abc-123, the server charges the card and stores the result under that key, the response is lost on the way back, the client retries with the same key, and the server finds abc-123 already done and returns the stored result instead of charging again.](/img/blog/retries-done-right/idempotency-key.webp)
*Same key, same result. The second attempt never charges the card.*

This is how payment APIs like Stripe handle it: you send an `Idempotency-Key` header, and a retry with the same key can't charge twice.

A simple version in your own app:

```php
// The client sends the same Idempotency-Key on every retry of one operation.
$key = $request->header('Idempotency-Key');

$result = DB::transaction(function () use ($key, $request) {
    // A unique index on idempotency_keys.key makes this the single source of truth.
    $existing = IdempotencyKey::where('key', $key)->lockForUpdate()->first();
    if ($existing) {
        return $existing->response;               // already done: return the same answer
    }

    $response = $this->createOrder($request);     // the real work, done once
    IdempotencyKey::create(['key' => $key, 'response' => $response]);

    return $response;
});
```

Two details that matter:

- **The key must be checked and saved atomically.** If two attempts arrive at the same moment and you "check, then do the work, then save the key" as separate steps, both can pass the check. Use a unique database index or a row lock. (This is the same check-then-act race from the [rate limiting article](/blog/rate-limiting-explained), and it's everywhere.)
- **Keys need a lifetime.** You don't need to keep them forever. Keep them as long as a client could reasonably retry, often 24 hours.

### Webhooks are retries you don't control

When a payment provider or GitHub sends you a webhook, they retry it if your server doesn't answer with a 2xx fast enough. Most providers promise **at-least-once** delivery, which means you *will* sometimes receive the same event twice.

So every webhook handler has to be idempotent:

- Store the provider's event ID with a unique index, and skip events you've already processed.
- Or make the action naturally idempotent, like "mark payment `abc` as completed" instead of "add credits".
- "If the status isn't completed yet, complete it" is a good start, but it's still check-then-act. Two copies of the same webhook arriving at once can both see "not completed". Do the check and the update inside one locked transaction, or as one conditional update (`UPDATE ... WHERE status != 'completed'`) and only continue if it changed a row.
- Answer quickly with a 200 and do the slow work in a queue, so the provider doesn't time out and retry while you're still working.

## Question 3: when, and how many times?

### Backoff: wait longer each time

If a service is struggling, hitting it again immediately makes it worse. **Exponential backoff** waits longer after each failure: 1 second, then 2, then 4, then 8, up to a maximum. It gives the other side room to recover.

### Jitter: don't all come back at once

Backoff alone has a hidden problem. Imagine a service blips and a thousand clients fail at the same moment. With plain exponential backoff, all thousand wait exactly 1 second and come back *at the same moment*. Then all wait exactly 2 seconds and come back together again. Each wave looks like a small attack.

![Without jitter, a thousand clients that failed together retry in tight spikes at 1, 2 and 4 seconds, each spike hitting the service at once. With full jitter, the same retries are spread evenly across each wait window, so the load stays flat.](/img/blog/retries-done-right/jitter.webp)
*Same number of retries. One shape hurts, the other doesn't.*

**Jitter** adds randomness to the wait so clients spread out. A well-known AWS Architecture Blog analysis compared several approaches, and one of the simplest did very well. It's called **full jitter**: pick a random wait between zero and the backoff value.

```js
// Full jitter: random wait between 0 and the exponential backoff, capped.
function backoffMs(attempt, baseMs = 500, capMs = 20_000) {
  const exp = Math.min(capMs, baseMs * 2 ** attempt);
  return Math.random() * exp;
}
```

```python
# Same idea in Python
def backoff_seconds(attempt: int, base: float = 0.5, cap: float = 20.0) -> float:
    return random.uniform(0, min(cap, base * 2 ** attempt))
```

### Respect `Retry-After`

If the server tells you when to come back, with a `Retry-After` header on a 429 or 503, use that instead of your own guess. It's both faster and kinder.

### Cap the attempts, and the time

Three or four attempts is plenty for most calls. Past that, you're mostly adding delay. And as the [timeouts article](/blog/timeouts-are-a-budget) showed, the real limit is time: every attempt and every wait has to fit in the deadline for the whole request.

## Retries multiply

A retry at one layer is fine. Retries at every layer multiply.

![Retry amplification: the browser tries 3 times, each request hits an API that tries 3 times, each of those calls a service that tries 3 times. One click becomes 27 calls to the struggling database at the bottom.](/img/blog/retries-done-right/amplification.webp)
*Three layers, three attempts each: 27 calls from one click.*

If the frontend retries 3 times, the API retries 3 times, and the service behind it retries 3 times, one user click can turn into **27 calls** to whatever is failing at the bottom. And that thing is failing because it's overloaded. The retries make sure it stays that way.

The rule: **retry at one layer only**, usually the one closest to the failing call, because it knows the most about the error. Every layer above it should pass the failure up instead of retrying again.

### Retry budgets

A retry budget limits retries to a share of normal traffic. For example, retries may never be more than 10% of all requests to a service. Google's SRE book describes this pattern. When things are healthy, the budget is barely used. When a service is failing badly, the budget runs out quickly, and you stop adding load to something that's already down.

### Circuit breakers

A circuit breaker takes this one step further. It watches failures to a dependency, and when they cross a threshold, it stops calling that dependency for a while and fails fast instead.

![Circuit breaker states: Closed lets requests through and counts failures; too many failures switch it to Open, which fails fast without calling the dependency; after a cooldown it moves to Half-open and lets a few test requests through; if they succeed it closes again, if they fail it opens again.](/img/blog/retries-done-right/circuit-breaker.webp)
*Fail fast while a dependency is down, then test it carefully.*

1. **Closed:** normal. Requests go through, failures are counted.
2. **Open:** too many failures. Requests fail immediately without calling the dependency. This protects it and keeps your users from waiting on something that won't answer.
3. **Half-open:** after a cooldown, a few test requests are let through. If they work, close the circuit. If not, open it again.

For an AI app with several providers, this pairs well with fallbacks. If one provider's circuit is open, go straight to the next one instead of spending time and retries on a provider you already know is down.

## Retries in background jobs

Queues retry for you, and that's a different kind of retry. If a job throws an error, the queue puts it back and runs it again later.

In Laravel it looks like this:

```php
class ExtractVideoJob implements ShouldQueue
{
    public int $tries = 2;      // total attempts
    public int $timeout = 600;  // seconds per attempt

    public function handle(): void { /* the work */ }

    // Called once, after the last attempt fails.
    public function failed(\Throwable $e): void
    {
        $this->video->user?->refundExtraction($this->consumedEntitlement);
    }
}
```

Things to know:

- **Assume every job can run twice.** Queues deliver at least once. A worker can crash, a deploy can kill a container halfway through, or a job can be picked up again after its lock expires. So jobs need to be idempotent too. Ask: if this job ran twice, what breaks?
- **Add a wait between attempts.** `public array $backoff = [30, 120];` waits 30 seconds before the second attempt and 120 before the third. Without it, a failed job can be retried right away, while the problem is still there.
- **Settings on the job win.** If the worker is started with `--tries=3` but the job class says `$tries = 2`, the job's value is used. Put the retry settings next to the code they protect.
- **Use `failed()` to clean up.** In YouExtractor, an extraction reserves a credit before the work starts. If every attempt fails, `failed()` gives the credit back, so nobody pays for an extraction that never produced anything.
- **Don't retry forever.** A job that fails on every attempt, often called a poison message, should end up somewhere you can look at it (Laravel's `failed_jobs` table, or a dead-letter queue), not keep cycling.
- **Retry the whole job, or the step?** If a job has five steps and step four fails, retrying the job repeats steps one to three. That's fine if they're cheap and idempotent, and expensive if they're AI calls. Saving progress after each step, or splitting into smaller jobs, can save a lot of tokens.

## Retrying an AI model is different

With AI APIs, there are two kinds of failure, and they need different retries.

![Two kinds of AI failure: a transport failure like a timeout, 429 or 503 is retried the normal way with backoff and jitter; a bad answer like invalid JSON or a missing field is retried with feedback, by sending the broken output back to the model and asking it to fix that specific problem, with a hard limit of one or two tries.](/img/blog/retries-done-right/ai-retries.webp)
*A broken connection and a broken answer need different retries.*

**Transport failures** are timeouts, 429s and 503s from the provider. Treat them like any other API: backoff, jitter, `Retry-After`, a cap, and fallback to another provider if one is down.

**Bad answers** are different. The call worked, but the output is wrong: invalid JSON, a missing field, a truncated file. Sending the exact same prompt again might work, but you pay full price for a coin flip.

What works better is **retrying with feedback**. YouExtractor asks the model for the project as JSON. When the JSON doesn't parse, it doesn't throw the whole answer away. It sends the broken output back to the same model with a short instruction: here is your answer, it isn't valid JSON, return the same content as valid JSON only. The model only has to fix the format, not redo the work, so it's a much smaller job than starting over.

A few rules for AI retries:

- **Prevent first.** If the provider supports a JSON or structured-output mode, use it. Fewer bad answers means fewer retries.
- **Clean up before you retry.** Models often wrap JSON in markdown fences or add a sentence before it. Strip those and try to parse again before spending another call.
- **Retry once or twice, not more.** If the model can't produce valid output after a correction, more attempts rarely help. Fall back to another model or fail clearly.
- **Count the tokens.** Every AI retry costs money and spends time from the same budget. Log them, so you can see when a prompt or model change suddenly doubles your retries.

## A checklist

- [ ] Only temporary errors are retried: connection failures, timeouts, 429, 502, 503, 504.
- [ ] Permanent errors (400, 401, 403, 404, 422) fail immediately.
- [ ] Anything that changes data is idempotent, or uses an idempotency key that is checked and saved atomically.
- [ ] Webhook handlers ignore events they've already processed.
- [ ] Retries use exponential backoff with jitter, respect `Retry-After`, and have a max number of attempts.
- [ ] Every retry fits inside the overall deadline.
- [ ] Retries happen at one layer only.
- [ ] There's a retry budget or circuit breaker for each important dependency.
- [ ] Queue jobs are safe to run twice, and `failed()` cleans up (refunds, statuses, alerts).
- [ ] AI calls handle bad answers separately from bad connections, with a hard limit.
- [ ] You log retries, so you can see when they suddenly go up.

## Frequently asked questions

### How many times should I retry?

For most API calls, 2 to 4 attempts in total. More attempts mostly add delay and load. What really limits you is the time budget for the whole request, so stop when that runs out, whatever the count says.

### What's the difference between exponential backoff and jitter?

Exponential backoff makes each wait longer than the last (1s, 2s, 4s). Jitter adds randomness to each wait, so many clients that failed at the same moment don't all retry at the same moment. You want both.

### Is it safe to retry a POST request?

Only if the server makes it idempotent, usually with an idempotency key. Otherwise a retry after a timeout can create a second order, payment or message, because the first one may have worked even though you never got the response.

### Should I retry a 500 Internal Server Error?

Sometimes. A 500 can be temporary or a real bug. One retry with backoff is reasonable for idempotent operations. If it fails the same way again, treat it as permanent. 502, 503 and 504 are more clearly temporary.

### What's a circuit breaker for if I already have retries?

Retries help with short, random failures. A circuit breaker helps when a dependency is properly down. It stops you from retrying at all, so you fail fast and give the dependency room to recover, instead of adding to its load.

## Related

- [Timeouts are a budget, not a number](/blog/timeouts-are-a-budget)
- [Rate limiting, from both sides](/blog/rate-limiting-explained)
- [How we turn YouTube tutorials into code](/blog/how-we-turn-youtube-tutorials-into-code)
