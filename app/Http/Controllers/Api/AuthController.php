<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Authenticate a user using email and password and
     * issue a permanent Sanctum personal access token.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:120'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if ($user === null || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('Credenciales inválidas.')],
            ]);
        }

        $deviceName = $this->resolveDeviceName(
            $credentials['device_name'] ?? null,
            $request
        );

        // Permanent tokens: do not pass expires_at.
        $token = $user->createToken($deviceName);

        return response()->json([
            'token' => $token->plainTextToken,
            'token_name' => $deviceName,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'must_change_password' => (bool) $user->must_change_password,
            ],
        ], 200);
    }

    /**
     * Issue a replacement Sanctum personal access token for the
     * authenticated user. Useful to rotate keys without changing
     * a password from external systems.
     */
    public function generateToken(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'device_name' => ['nullable', 'string', 'max:120'],
        ]);

        $user = $request->user();

        abort_unless($user !== null, 401);

        $deviceName = $this->resolveDeviceName(
            $payload['device_name'] ?? null,
            $request
        );

        $token = $user->createToken($deviceName);

        return response()->json([
            'token' => $token->plainTextToken,
            'token_name' => $deviceName,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            ],
        ], 200);
    }

    /**
     * Revoke the access token currently used by the request.
     */
    public function revokeToken(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user !== null, 401);

        $token = $user->currentAccessToken();

        if ($token !== null && method_exists($token, 'delete')) {
            $token->delete();
        } else {
            $user->tokens()->delete();
        }

        return response()->json([
            'message' => __('Token revocado correctamente.'),
        ], 200);
    }

    /**
     * Return the authenticated user payload.
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user !== null, 401);

        $tokens = $user->tokens()
            ->orderByDesc('last_used_at')
            ->limit(10)
            ->get(['id', 'name', 'last_used_at', 'created_at']);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'must_change_password' => (bool) $user->must_change_password,
                'tokens' => $tokens,
            ],
        ], 200);
    }

    /**
     * Destroy the current Sanctum token, effectively logging out
     * the bearer-token client. Falls back to flushing all tokens
     * when no current access token is bound to the request.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user !== null, 401);

        $token = $user->currentAccessToken();

        if ($token !== null && method_exists($token, 'delete')) {
            $token->delete();
        }

        return response()->json(['message' => __('Sesión API cerrada.')], 200);
    }

    /**
     * Determine a stable, human readable device name used to label
     * the personal access token. Falls back to a generated slug so
     * tokens are always identifiable in the database.
     */
    protected function resolveDeviceName(?string $provided, Request $request): string
    {
        if (is_string($provided) && trim($provided) !== '') {
            return Str::limit(trim($provided), 120, '');
        }

        $userAgent = $request->userAgent();
        $agent = is_string($userAgent) && trim($userAgent) !== ''
            ? Str::limit(trim($userAgent), 80, '')
            : 'api-client';

        return $agent.'-'.Str::lower(Str::random(6));
    }
}
