---
title: "A User Sent Me a Mail Requesting to Delete His Account. I Started Wondering What Went Wrong, and This Is What I Found"
date: 2026-11-07
excerpt: A user emailed me to delete his account. I wanted to know why, so I started digging through my server. What I found was that for a whole month, my app had been guessing the code instead of extracting it from the video, and telling everyone it worked. This is the full story, step by step.
reading_time: 8
image: /img/blog/a-user-asked-me-to-delete-their-account/cover.png
image_alt: A developer at his desk at night, looking at an email on his laptop that says Please delete my account
---

I received a mail from someone asking me to delete his account on YouExtractor, my AI app. He said he tried it, it was good, but it was not what he was looking for.

I could have just deleted the account and moved on. People try things and leave, that's normal, and I get it. But something about it stayed with me. He said the app was good. So why leave after one try? I started wondering what really happened for him to say that. What did he actually see when he used it?

So before deleting anything, I opened his project. That one decision is the reason this article exists.

## First, what YouExtractor does

If you haven't used it: YouExtractor extracts the code from YouTube coding tutorials. You paste a video link, and it gives you the code files, what to install, and how to run it, so you don't have to pause the video every few seconds and type everything by hand.

To do that, the app does two things:

1. It reads what the person in the video is saying. This is the video's text, the captions.
2. It gives that text to an AI, which pulls out the code, the files, and the setup steps.

Step one is everything. If the app can't read the video, the AI has nothing real to work with.

## What I saw in his project

His project looked fine at first. Twelve Python files, a setup guide, a list of things to install. If you looked quickly, you would think it worked.

But the app saves the video text with every project, so I opened it. It was just one line:

> Transcript not available. Extraction based on video title and metadata.

That's all. The app never read the video. It only saw the title, guessed what the code might be, and told him "Done".

The video was about building a game with an AI coding tool. The code we gave him was a guess of what a video with that title *might* contain. No wonder it wasn't what he was looking for. It wasn't what the video was about at all.

My first thought was: okay, maybe this one video had no captions. That happens. Some videos really don't have them.

So I checked the project before his. Same line. And the one before that. Same line.

I went to the database and counted. From 10 October to 6 November, there were 29 projects. **All 29 had that same line.** Not one of them was extracted from the real video. When I looked further back, I found a few older ones from June with the same problem too.

For four weeks, every single new user got a guessed project, and the app told every one of them it was done.

That one hurt.

## "But this was working before"

This was the part I couldn't understand. The app had worked well for months. I didn't change anything in October. So what happened?

I started debugging the server to find out.

![A developer's laptop showing server logs: the caption service says API key required, the YouTube captions come back empty, the YouTube page asks to prove it's not a bot, and the app still says completed.](/img/blog/a-user-asked-me-to-delete-their-account/logs.webp)

The app has three ways to get the text of a video. It tries the first one. If that fails, it tries the second. If that fails, the third. I tested each one by hand, one by one, from the server.

**The first way** is a caption service from another company. This one did almost all the work for months. When I sent it a request, it answered with an error that basically said "API key required". I checked, and on 9 October they changed their rules. Before, anyone could use it without a key. Now you need one. My app was still sending requests without a key, so every single request was refused.

That explained why it started on 9 October. But it didn't explain why the other two ways didn't save us.

**The second way** is an old YouTube link that returns captions. I tried it from the server. It came back empty. Not an error, just nothing. Then I tried it from my own laptop, and it was empty there too. YouTube had changed it at some point, and it simply doesn't work anymore.

**The third way** is reading the captions straight from the YouTube video page. From my laptop at home, this one worked fine. But from the server, YouTube showed a page saying "Sign in to confirm you're not a bot." YouTube is much stricter with servers than with normal people at home. So from the server, this way was dead too.

![Three ways to read the video, and all three failed: the caption service started asking for an API key, the old YouTube link sends back nothing, and the YouTube page blocks the server as a bot. So the app guessed from the title and said done.](/img/blog/a-user-asked-me-to-delete-their-account/three-ways.webp)

So my "three ways" were really one way. The two backups had stopped working a long time ago, and I never knew, because the first way never needed them. When the first way stopped working, there was nothing behind it.

## Why I didn't notice for a whole month

This is the part I keep thinking about, because a service changing its rules is normal. That happens to everyone. What turned it into a month of bad projects was what my app did next.

When all three ways failed, the app didn't stop and show an error. It was written to "still give the user something". So it saved that one line, "Transcript not available", and then asked the AI to make a project from just the title and the video description.

And the AI did it. It always does. It wrote files, a README, setup steps, and it all looked confident and clean. The job finished, and the status said "Done".

Look at it from my side. My dashboard showed extractions completing. No errors. My logs showed the app "trying" each way, then moving on, but never said *why* each one failed. If anything was counting failures, it counted zero, because nothing technically failed.

Now look at it from the user's side. You paste a video, you wait, you get code that has nothing to do with the video. What do you do? You don't write a bug report to a small app you just tried. You think "this tool is not good" and you leave. That's exactly what happened, 29 times.

![Timeline: 9 October was the last real video text, from 10 October to 6 November 29 out of 29 projects were guessed, on 6 November a user asked to delete his account, and on 7 November it was found, fixed, and users were emailed.](/img/blog/a-user-asked-me-to-delete-their-account/timeline.webp)

Only one person wrote to me, and even he didn't write to complain. He wrote to delete his account. If he had just left quietly like the others, I would still not know.

## Fixing the main problem

Once I knew the cause, the fix itself was not hard.

I went to the caption service and got an API key. The free plan allows 10 videos a day. I checked my numbers, and my busiest day last month had 4 extractions, so 10 a day is enough for now. When I grow past that, I'll move to a paid plan.

I put the key in the server settings, not in the code, and changed the app to send it with every request. Then I deployed it.

I also changed one more thing while I was there: the logs. Before, when a way failed, the log just said "trying the next one". Now, it writes down exactly what went wrong, like "the caption service answered: API key required". If I had that one line a month ago, I would have found this on the first day, not after four weeks.

Then I tested it. I used the same video the user had tried, the one that gave him a guessed project. This time the app read the whole video: 8,708 characters of real text, instead of one line saying "not available".

About ten minutes after the fix went live, a new user signed up and tried the app. I watched it happen. The app read their video, all 82,000 characters, and their code was extracted from the real video. After the day I'd had, that felt really good.

## Making it right for the users

Fixing the code only helps new users. The 29 people who already got a guessed project still had a bad project, and for most of them, it was their **one free extraction**, used up on nothing.

So I made a list of everyone affected, and decided to do three things for each person:

1. Extract their project again, properly this time, from the real video.
2. Add a free extraction back to their account.
3. Send them an email to say sorry, explain what happened, and give them the link to their fixed project.

The first part was slower than I wanted. The free key only allows 10 videos a day, and I still needed room for new users. So I'm doing it in small groups, a few videos each day, starting with the newest users first, because their bad experience is the most recent.

For each group, I didn't want to just trust that it worked. Before emailing anyone, I check each project: is it finished, does it have the real video text and not the "not available" line, and does it actually have code files? Only when all of a person's projects pass that check do they get their email.

Two of the videos turned out not to be coding tutorials at all. No captions in the world could turn those into a real code project. Those users still got the apology and the free extraction, but with an honest explanation: this video isn't a coding tutorial, so there's no code to extract.

Before sending anything, I ran it in test mode so it printed every email instead of sending it, and I read them all. That caught two mistakes: one user was missing from the list, and the emails for the two non-coding videos wrongly said "we couldn't read the captions". I fixed both, then sent the first group. Eight people have their fixed projects now, and the rest will get theirs over the next few days.

### The user who started all this

Last, I went back to the person who emailed me. I deleted his account and his project, like he asked. Then I sent him a reply. I told him what really went wrong with his extraction, that it wasn't his fault or his video, that it's fixed now, and I thanked him. His short, polite mail is the reason 29 people are getting a proper project and an apology instead of nothing.

## One more thing I found on the same day

While I was reading the server logs for the caption problem, I kept seeing another error. It showed up every morning at exactly 09:00.

It came from the job that sends reminder emails to users. I scrolled back as far as the logs go, to early September. It failed every single morning. That means not one reminder email had ever gone out. I didn't know, because nothing told me.

The error said "Permission denied" while connecting to the database. That was strange, because the website itself connects to the same database all day without any problem.

The cause was small. The reminder job runs as a user with limited permissions, but its home folder was set to the admin's folder. The database tool looks in the home folder for an optional file, wasn't allowed in, and gave up.

The fix was one line in the server settings: give the reminder job its own home folder that it's allowed to read.

Before turning it back on, I made it less annoying: reminders now go out every three days instead of every day, and each one has an unsubscribe link.

It's the same lesson as the caption problem, in a different place: something was broken for two months, and nothing was watching it.

## What I'm taking from all this

**A wrong answer that says "Done" is worse than an honest error.** The guessing was meant to help, but it hid the problem, wasted users' free extraction, and lost their trust. So the next change: if the app can't read the video, it will say so clearly and give the extraction back. No more guessing.

**Write down why something failed.** "Trying the next one" told me nothing. "API key required" would have told me everything.

**Test your backups.** Two of my three ways had been dead for months, and I only found out when I needed them.

**Read every "please delete my account" mail closely.** It was the only warning I got, and I almost just clicked delete.

## If you used YouExtractor in October

If you extracted a project between 10 October and 6 November, it was affected. You should get an email from me with your fixed project and a free extraction added to your account. Some are still being extracted again over the next few days, so if yours hasn't come yet, it's on the way.

If you don't get anything by the end of the week, reach out on the [support page](/support) and I'll sort it out for you personally.

And to the person who sent me that mail: thank you.
