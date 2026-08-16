// YouExtractor — background service worker
//
// All API calls to the YouExtractor website happen HERE (extension origin),
// which bypasses CORS entirely. The session cookie is handled natively by the
// browser: the site's session cookie is SameSite=None;Secure (see
// config/session.php / render.yaml) and every fetch uses
// `credentials: 'include'`, so the browser attaches the cookie itself.
// (Setting the Cookie header from fetch() directly is impossible — Chrome
// strips it as a forbidden header.)
//
// In-panel auth (login/register) also uses `credentials: 'include'`, so the
// Set-Cookie session cookie from the response lands in the browser jar and is
// then attached automatically on every subsequent request.
//
// To point this at a self-hosted instance, change APP_BASE below.

const APP_BASE = 'https://youextractor.me'; // Update if self-hosted

// ---------------------------------------------------------------------------
// Cookie / fetch plumbing
// ---------------------------------------------------------------------------

/**
 * Reads a named (non-session) cookie for the app domain, e.g. the
 * "last_used_auth" hint cookie set by the website.
 */
async function getCookie(name) {
    const hosts = [APP_BASE + '/', APP_BASE.replace('//', '//www.') + '/'];
    for (const url of hosts) {
        try {
            const cookies = await chrome.cookies.getAll({ url });
            const found = cookies.find((c) => c.name === name);
            if (found) return found.value;
        } catch (err) {
            console.error('[YouExtractor] cookies.getAll failed:', err);
        }
    }
    return null;
}

/**
 * fetch() against the app API.
 *
 * Always uses `credentials: 'include'` so the browser attaches the site's
 * SameSite=None session cookie natively — never set the Cookie header from
 * fetch() directly, Chrome strips it.
 *
 * Returns { ok, status, data } where data is the parsed JSON body.
 */
async function apiFetch(path, options = {}) {
    const { useCookies: _omit, timeout: timeoutMs = 20000, ...fetchOptions } = options;

    const headers = {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(options.headers || {}),
    };

    // Never let a stalled request hold the message channel open forever —
    // if the fetch hangs, the service worker could be killed with the
    // response undelivered and the content script would wait indefinitely.
    // Long operations (e.g. extraction) pass a larger `timeout` option.
    const controller = new AbortController();
    const timer = setTimeout(() => controller.abort(), timeoutMs);

    let response;
    try {
        response = await fetch(APP_BASE + path, {
            ...fetchOptions,
            headers,
            credentials: 'include',
            signal: controller.signal,
        });
    } catch (err) {
        console.error('[YouExtractor] network error:', err);
        return { ok: false, status: 0, data: { error: 'Network error — is youextractor.me reachable?' } };
    } finally {
        clearTimeout(timer);
    }

    let data = {};
    try {
        data = await response.json();
    } catch (err) {
        data = { error: `Unexpected response (HTTP ${response.status})` };
    }

    return { ok: response.ok, status: response.status, data };
}

/** Pull the first human-readable validation error out of a 422 payload. */
function firstValidationError(data) {
    if (data && data.errors) {
        const key = Object.keys(data.errors)[0];
        if (key && data.errors[key]) return Array.isArray(data.errors[key]) ? data.errors[key][0] : data.errors[key];
    }
    return (data && data.error) || 'Something went wrong. Please try again.';
}

// ---------------------------------------------------------------------------
// Message handlers (called from the content script)
// ---------------------------------------------------------------------------

chrome.runtime.onMessage.addListener((message, _sender, sendResponse) => {
    handleMessage(message)
        .then((result) => sendResponse(result))
        .catch((err) => {
            console.error('[YouExtractor] background error:', err);
            sendResponse({ ok: false, error: err.message || 'Background error' });
        });
    // Keep the message channel open for the async response.
    return true;
});

async function handleMessage(message) {
    switch (message && message.type) {
        case 'checkAuth': {
            // GET /api/me -> 200 with user info when logged in, 401 otherwise.
            const res = await apiFetch('/api/me');
            const lastUsed = await getCookie('last_used_auth');
            if (res.ok && res.data && res.data.data) {
                const u = res.data.data;
                return {
                    ok: true,
                    authenticated: true,
                    name: u.name,
                    email: u.email,
                    avatar: u.avatar || null,
                    lastUsed,
                };
            }
            return { ok: true, authenticated: false, lastUsed };
        }

        case 'login': {
            const res = await apiFetch('/api/auth/login', {
                method: 'POST',
                useCookies: true,
                body: JSON.stringify({
                    email: message.email,
                    password: message.password,
                    remember: !!message.remember,
                }),
            });
            if (!res.ok) return { ok: false, error: firstValidationError(res.data) };
            return { ok: true, name: res.data.data.name, email: res.data.data.email, avatar: res.data.data.avatar || null };
        }

        case 'register': {
            const res = await apiFetch('/api/auth/register', {
                method: 'POST',
                useCookies: true,
                body: JSON.stringify({
                    name: message.name,
                    email: message.email,
                    password: message.password,
                    password_confirmation: message.passwordConfirmation,
                }),
            });
            if (!res.ok) return { ok: false, error: firstValidationError(res.data) };
            return { ok: true, name: res.data.data.name, email: res.data.data.email, avatar: res.data.data.avatar || null };
        }

        case 'logout': {
            const res = await apiFetch('/api/auth/logout', { method: 'POST' });
            return { ok: true, loggedOut: res.ok || res.status === 401 };
        }

        case 'extractVideo': {
            // The site runs the whole AI extraction before answering, so give
            // this request up to 10 minutes (well past the 20s default).
            const res = await apiFetch('/api/videos/extract', {
                method: 'POST',
                body: JSON.stringify({ youtube_url: message.url }),
                timeout: 600000,
            });
            if (!res.ok) {
                if (res.status === 401) {
                    return { ok: false, error: 'unauthorized' };
                }
                return { ok: false, error: (res.data && res.data.error) || 'Extraction failed' };
            }
            return { ok: true, video: res.data.data, cached: !!res.data.cached, message: res.data.message };
        }

        case 'videoStatus': {
            const res = await apiFetch(`/api/videos/${message.id}/status`);
            if (!res.ok) {
                if (res.status === 401) return { ok: false, error: 'unauthorized' };
                return { ok: false, error: (res.data && res.data.error) || 'Status check failed' };
            }
            return {
                ok: true,
                status: res.data.status,
                error: res.data.error,
                video: res.data.data || null,
            };
        }

        default:
            return { ok: false, error: `Unknown message type: ${message && message.type}` };
    }
}
