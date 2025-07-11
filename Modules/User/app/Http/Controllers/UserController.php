<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Http\Requests\UserListRequest;
use Modules\User\Models\User;
use Modules\User\Services\UserService;
use Modules\User\Transformers\UserResource;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}


    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get a paginated list of users with filter on name or email",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Filter users by name",
     *         required=false,
     *         @OA\Schema(type="string", example="John")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Filter users by email",
     *         required=false,
     *         @OA\Schema(type="string", format="email", example="john@example.com")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10 ,default=15)
     *     ),
     *  *     @OA\Response(
     *         response=200,
     *         description="Paginated list of users",
     *         @OA\JsonContent(ref="#/components/schemas/GenericPaginatedResponse")
     *     )

     * )
     */

    public function index(UserListRequest $request)
    {
        $perPage = $request->input('per_page', 15);
        $filters = $request->only(['name', 'email']);

        $users = $this->userService->getUsers($filters, $perPage);
        $users = UserResource::collection($users)->response()->getData();

        return $this->successResponse($users);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{user}",
     *     summary="Get a single user with their posts",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="The ID of the user",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful user fetch",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/UserWithPosts"),
     *             @OA\Property(property="meta", type="string", nullable=true, example=null),
     *             @OA\Property(property="links", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function show(User $user)
    {
        $user = $this->userService->show($user);
        $user = UserResource::make($user);
        return $this->successResponse($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
