<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\DTOs\LoginDto;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Services\AuthService;
use Modules\User\Transformers\UserResource;

class AuthController extends Controller
{
  public function __construct(protected AuthService $authService) {}

  /**
   * @OA\Post(
   *     path="/api/mock-login",
   *     summary="Mock login with role-based authorization",
   *     tags={"Authentication"},
   *     description="Authenticate a user with email and role, returning user info and token.",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"email","role"},
   *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User email"),
   *             @OA\Property(property="role", type="string", enum={"admin", "editor", "user"}, example="user", description="Role of the user")
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="Successful login",
   *         @OA\JsonContent(
   *             @OA\Property(property="data", type="object",
   *                 @OA\Property(property="user", ref="#/components/schemas/User"),
   *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...")
   *             ),
   *             @OA\Property(property="message", type="string", example="Success")
   *         )
   *     ),
   *     @OA\Response(
   *         response=422,
   *         description="Validation error",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="The given data was invalid."),
   *             @OA\Property(property="errors", type="object")
   *         )
   *     )
   * )
   */
  public function mockLogin(LoginRequest $request)
  {
    $loginDto = LoginDto::fromArray($request->validated());

    $result = $this->authService->login($loginDto);

    $data['user'] = UserResource::make($result);
    $data['token'] = $result['token'];

    return $this->successResponse($data);
  }
}
