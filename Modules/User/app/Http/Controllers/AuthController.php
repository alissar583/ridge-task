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
   *     description="Authenticate or register a user using email and role. If a user exists with the same email and the same role, a token is returned. If the email exists with a different role, a validation error is returned. If the email does not exist, a new mock user will be created.",
   *     operationId="mockLogin",
   *     tags={"Authentication"},
   *
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"email", "role"},
   *             @OA\Property(
   *                 property="email",
   *                 type="string",
   *                 format="email",
   *                 example="user@example.com",
   *                 description="The user's email address"
   *             ),
   *             @OA\Property(
   *                 property="role",
   *                 type="string",
   *                 enum={"admin", "editor", "user"},
   *                 example="editor",
   *                 description="The user's role"
   *             )
   *         )
   *     ),
   *
   *     @OA\Response(
   *         response=200,
   *         description="Successful login or mock registration",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(
   *                 property="data",
   *                 type="object",
   *                 @OA\Property(property="user", ref="#/components/schemas/User"),
   *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOi...")
   *             ),
   *             @OA\Property(property="message", type="string", example="Success")
   *         )
   *     ),
   *
   *     @OA\Response(
   *         response=422,
   *         description="Validation error - Email exists with different role or required fields missing",
   *         @OA\JsonContent(
   *             type="object",
   *             @OA\Property(property="message", type="string", example="The given data was invalid."),
   *             @OA\Property(
   *                 property="errors",
   *                 type="object",
   *                 @OA\Property(
   *                     property="email",
   *                     type="array",
   *                     @OA\Items(type="string", example="This email is already used with a different role. Please use another email.")
   *                 )
   *             )
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
