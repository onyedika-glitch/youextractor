<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(protected UserNotifier $notifier)
    {
    }

    /**
     * Show the signup form.
     * Supports prefill youtube_url from demo / Chrome extension.
     */
    public function showSignup(Request $request)
    {
        $prefillUrl = $request->query('youtube_url') ?: $request->query('url');
        $lastUsedAuth = $request->cookie('last_used_auth');
        return view('auth.signup', ['prefillUrl' => $prefillUrl, 'lastUsedAuth' => $lastUsedAuth]);
    }

    /**
     * Handle user registration.
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send the onboarding welcome email to the new user.
        $this->notifier->welcome($user);

        Auth::login($user);

        $redirect = redirect()->route('dashboard');
        $prefill = $request->input('youtube_url') ?: $request->input('url') ?: $request->session()->get('prefill_youtube_url');
        if ($prefill) {
            $redirect = redirect()->route('dashboard', ['youtube_url' => $prefill]);
        }
        return $redirect->withCookie(cookie()->forever('last_used_auth', 'email'))->with('success', 'Welcome to YouTube Code Extractor!');
    }

    /**
     * Show the signin form.
     * Supports prefill youtube_url from demo / Chrome extension.
     */
    public function showSignin(Request $request)
    {
        $prefillUrl = $request->query('youtube_url') ?: $request->query('url');
        $lastUsedAuth = $request->cookie('last_used_auth');
        return view('auth.signin', ['prefillUrl' => $prefillUrl, 'lastUsedAuth' => $lastUsedAuth]);
    }

    /**
     * Handle user login.
     */
    public function signin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Notify the user of a successful sign-in.
            $this->notifier->activity(Auth::user(), 'login', $request);

            $redirect = redirect()->intended(route('dashboard'));
            $prefill = $request->input('youtube_url') ?: $request->input('url');
            if ($prefill) {
                $redirect = redirect()->route('dashboard', ['youtube_url' => $prefill]);
            }
            return $redirect->withCookie(cookie()->forever('last_used_auth', 'email'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Redirect to Google for authentication.
     */
    public function redirectToGoogle()
    {
        $driver = Socialite::driver('google');
        if (config('app.env') !== 'production') {
            $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        }
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forever('last_used_auth', 'google'));
        return $driver->redirect();
    }

    /**
     * Handle Google callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $driver = Socialite::driver('google');
            if (config('app.env') !== 'production') {
                $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
            }
            $googleUser = $driver->user();

            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Update Google ID if user exists but signed up with email
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);

                // Onboard brand-new Google users.
                $this->notifier->welcome($user);
            }

            Auth::login($user, true);

            // Notify of the sign-in via Google.
            $this->notifier->activity($user, 'login', request(), ['method' => 'Google']);

            return redirect()->route('dashboard')
                ->withCookie(cookie()->forever('last_used_auth', 'google'))
                ->with('success', 'Welcome!');
        } catch (\Exception $e) {
            error_log('Google Auth Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            \Illuminate\Support\Facades\Log::error('Google Auth Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('signin')->withErrors(['error' => 'Google authentication failed. Please try again.']);
        }
    }

    /**
     * Redirect to GitHub for authentication.
     */
    public function redirectToGitHub()
    {
        $driver = Socialite::driver('github');
        if (config('app.env') !== 'production') {
            $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        }
        \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forever('last_used_auth', 'github'));
        return $driver->redirect();
    }

    /**
     * Handle GitHub callback.
     */
    public function handleGitHubCallback()
    {
        try {
            $driver = Socialite::driver('github');
            if (config('app.env') !== 'production') {
                $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
            }
            $githubUser = $driver->user();

            $user = User::where('github_id', $githubUser->id)
                        ->orWhere('email', $githubUser->email)
                        ->first();

            if ($user) {
                // Update GitHub ID if user exists but signed up with email/Google
                if (!$user->github_id) {
                    $user->update([
                        'github_id' => $githubUser->id,
                        'avatar' => $githubUser->avatar ?? $user->avatar,
                    ]);
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $githubUser->name ?? $githubUser->nickname ?? 'GitHub User',
                    'email' => $githubUser->email,
                    'github_id' => $githubUser->id,
                    'avatar' => $githubUser->avatar,
                ]);

                // Onboard brand-new GitHub users.
                $this->notifier->welcome($user);
            }

            Auth::login($user, true);

            // Notify of the sign-in via GitHub.
            $this->notifier->activity($user, 'login', request(), ['method' => 'GitHub']);

            return redirect()->route('dashboard')
                ->withCookie(cookie()->forever('last_used_auth', 'github'))
                ->with('success', 'Welcome!');
        } catch (\Exception $e) {
            error_log('GitHub Auth Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            \Illuminate\Support\Facades\Log::error('GitHub Auth Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('signin')->withErrors(['error' => 'GitHub authentication failed. Please try again.']);
        }
    }

    /**
     * Return the authenticated user (used by the Chrome extension to
     * detect whether the visitor is signed in).
     * Only reachable behind the "auth" middleware -> 401 JSON when logged out.
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id'     => $request->user()->id,
                'name'   => $request->user()->name,
                'email'  => $request->user()->email,
                'avatar' => $request->user()->avatar,
            ],
        ]);
    }

    /**
     * JSON sign-in used by the Chrome extension's in-panel auth form.
     * Same credentials + session behaviour as the web form, but returns JSON
     * and lets the browser store the session cookie (credentials: include).
     */
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $this->notifier->activity(Auth::user(), 'login', $request, ['method' => 'Extension']);

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'     => Auth::id(),
                    'name'   => Auth::user()->name,
                    'email'  => Auth::user()->email,
                    'avatar' => Auth::user()->avatar,
                ],
            ])->withCookie(cookie()->forever('last_used_auth', 'email'));
        }

        return response()->json([
            'success' => false,
            'error'   => 'The provided credentials do not match our records.',
        ], 422);
    }

    /**
     * JSON registration used by the Chrome extension's in-panel sign-up form.
     */
    public function apiRegister(Request $request)
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $this->notifier->welcome($user);

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'avatar' => $user->avatar,
            ],
        ], 201)->withCookie(cookie()->forever('last_used_auth', 'email'));
    }

    /**
     * JSON logout used by the Chrome extension's panel.
     */
    public function apiLogout(Request $request)
    {
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            $this->notifier->activity($user, 'logout', $request, ['method' => 'Extension']);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        // Capture the user before the session is cleared so we can notify them.
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            $this->notifier->activity($user, 'logout', $request);
        }

        return redirect()->route('landing')->with('success', 'You have been logged out.');
    }
}