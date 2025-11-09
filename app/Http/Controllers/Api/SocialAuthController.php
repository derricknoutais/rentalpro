<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\OauthLoginCode;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class SocialAuthController extends Controller
{
    /**
     * The supported OAuth providers.
     *
     * @var array<int, string>
     */
    protected array $providers;

    public function __construct()
    {
        $this->providers = config('services.oauth.providers', ['google']);
    }

    /**
     * Return the provider redirect URL so the SPA can initiate OAuth.
     */
    public function redirectUrl(string $provider): JsonResponse
    {
        $this->ensureProviderIsAllowed($provider);

        $url = Socialite::driver($provider)
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return response()->json(['url' => $url]);
    }

    /**
     * Handle the OAuth callback, generate a one-time code, and
     * redirect back to the SPA with that code in the query string.
     */
    public function handleCallback(Request $request, string $provider)
    {
        $this->ensureProviderIsAllowed($provider);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Throwable $exception) {
            Log::error('Socialite callback failed', [
                'provider' => $provider,
                'error' => $exception->getMessage(),
            ]);

            return $this->redirectToFrontend([
                'error' => 'oauth_callback_failed',
            ]);
        }

        $user = $this->resolveUserFromSocialite($socialUser);

        $code = OauthLoginCode::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'code' => Str::random(64),
            'meta' => [
                'social_id' => $socialUser->getId(),
                'email' => $socialUser->getEmail(),
            ],
            'expires_at' => now()->addSeconds((int) config('services.oauth.code_ttl', 120)),
        ]);

        return $this->redirectToFrontend([
            'code' => $code->code,
            'provider' => $provider,
        ]);
    }

    /**
     * Exchange a one-time OAuth code for a Sanctum token.
     */
    public function exchangeToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        /** @var OauthLoginCode|null $loginCode */
        $loginCode = OauthLoginCode::where('code', $validated['code'])->first();

        if (! $loginCode || $loginCode->isExpired() || $loginCode->isConsumed()) {
            return response()->json([
                'message' => 'Invalid or expired code.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $loginCode->forceFill([
            'consumed_at' => now(),
        ])->save();

        $token = $loginCode->user->createToken('rentalpro_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $loginCode->user,
        ]);
    }

    /**
     * Resolve a local user from the Socialite payload.
     */
    protected function resolveUserFromSocialite($socialUser): User
    {
        return User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'Utilisateur',
                'password' => Hash::make(Str::random(32)),
            ]
        );
    }

    protected function ensureProviderIsAllowed(string $provider): void
    {
        if (! in_array($provider, $this->providers, true)) {
            abort(404, 'Unsupported OAuth provider.');
        }
    }

    protected function redirectToFrontend(array $queryParams)
    {
        $frontend = rtrim(
            config('services.oauth.frontend_redirect', config('app.frontend_url', 'http://localhost:5173')),
            '/'
        );

        $query = http_build_query($queryParams);

        return redirect()->away("{$frontend}?{$query}");
    }
}
