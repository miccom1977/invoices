<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\TokenRepository;
use Illuminate\Support\Facades\Auth;

readonly class AuthService
{
    public function __construct(
        private TokenRepository $tokenRepository
    )
    {}

    /** Metoda odpowiada za logowanie do systemu
     * @param array $credentials
     * @return array
     */
    public function login(array $credentials): array
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AccessToken')->plainTextToken;

            return [
                'token' => $token,
                'user' => $user->only(['id', 'name', 'email']),
                'error' => null
            ];
        } else {
            return [
                'token' => null,
                'error' => __('auth.failed'),
            ];
        }
    }

    /** Metoda odpowiada za wylogowanie usera z systemu i usunięcie tokena sanctum
     * @return true
     */
    public function logout(): true
    {
        if ($user = Auth::user()) {
            $this->tokenRepository->deleteTokensByUserId($user->id);
        }
        return true;
    }

    /** Metoda pobiera dane zalogowanego usera
     * @param User $user
     * @return array
     */
    public function getUserData(User $user): array
    {
        return $user->only(['id', 'name', 'email']);
    }
}
