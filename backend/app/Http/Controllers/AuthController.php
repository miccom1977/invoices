<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {}

    /** Metoda odpowiada za logowanie użytkownika
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Błędne dane logowania',
                'errors' => $validator->errors()
            ], 422);
        }
        $authResult = $this->authService->login($request->only('email', 'password'));
        if ($authResult['token']) {
            return response()->json([
                'user' => $authResult['user'],
                'token' => $authResult['token']
            ]);
        } else {
            return response()->json(['error' => $authResult['error']], 403);
        }
    }

    /** Metoda sprawdza, czy user jest zalogowany
     * @param Request $request
     * @return JsonResponse
     */
    public function ping(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(
            $this->authService->getUserData($user)
        );
    }

    /** Metoda odpowiada za wylogowanie użytkownika
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return response()->json(['message' => 'Wylogowano pomyślnie']);
    }
}
