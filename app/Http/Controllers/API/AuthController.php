<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @OA\Info(title="Reclamos API", version="1.0.0", description="API para gestión de reclamos")
 *
 * @OA\Tag(
 *   name="Auth",
 *   description="Operaciones de autenticación"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="Bearer"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/login",
     *   tags={"Auth"},
     *   summary="Iniciar sesión y obtener token",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *       @OA\Property(property="password", type="string", format="password", example="password")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Credenciales válidas",
     *     @OA\JsonContent(
     *       @OA\Property(property="user", type="object", example={"id":1,"name":"Juan","email":"juan@example.com"}),
     *       @OA\Property(property="token", type="string"),
     *       @OA\Property(property="token_type", type="string", example="Bearer")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Credenciales inválidas")
     * )
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user->only('id', 'name', 'email'),
            'token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    /**
     * @OA\Post(
     *   path="/api/logout",
     *   tags={"Auth"},
     *   summary="Cerrar sesión (revocar token actual)",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=200, description="Sesión cerrada"),
     *   @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $token->delete();
        }
        return response()->json(['message' => 'Sesión cerrada'], 200);
    }

    /**
     * @OA\Get(
     *   path="/api/me",
     *   tags={"Auth"},
     *   summary="Obtener usuario autenticado",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Usuario autenticado",
     *     @OA\JsonContent(
     *       type="object",
     *       example={"id":1,"name":"Juan","email":"juan@example.com"}
     *     )
     *   ),
     *   @OA\Response(response=401, description="No autorizado")
     * )
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
