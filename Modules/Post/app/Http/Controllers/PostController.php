<?php

namespace Modules\Post\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\Post\DTOs\CreatePostDto;
use Modules\Post\Http\Requests\PostListRequest;
use Modules\Post\Http\Requests\StorePostRequest;
use Modules\Post\Models\Post;
use Modules\Post\Services\PostService;
use Modules\Post\Transformers\PostResource;

class PostController extends Controller
{
    public function __construct(protected PostService $postService) {}

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get a paginated list of posts",
     *     description="Returns a paginated list of posts. Supports optional pagination.",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of posts per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10, default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of posts",
     *         @OA\JsonContent(ref="#/components/schemas/GenericPaginatedResponse")
     *     )
     * )
     */
    public function index(PostListRequest $request)
    {
        $perPage = $request->input('per_page', 15);

        $posts = $this->postService->getPosts($perPage);
        $posts = PostResource::collection($posts)->response()->getData();

        return $this->successResponse($posts, "Paginated list of posts");
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *      description="Creates a new post. Only authenticated users with the 'editor' or 'admin' role are allowed to access this endpoint.",

     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="My first post"),
     *             @OA\Property(property="description", type="string", nullable=true, maxLength=10000, example="This is a detailed description of the post.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Post created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Post"),
     *             @OA\Property(property="meta", type="string", nullable=true, example=null),
     *             @OA\Property(property="links", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The title field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *  *     @OA\Response(
     *         response=403,
     *         description="Forbidden - User does not have the 'editor' or 'admin' role",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function store(StorePostRequest $request)
    {
        $dto = CreatePostDto::fromArray(
            $request->validated(),
            auth()->id()
        );

        $post = $this->postService->store($dto);
        $post = PostResource::make($post);

        return $this->successResponse($post);
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a specific post",
     *     description="Deletes a post by its ID. Role-based access (admin or editor), and ownership verification through policy authorization. Only the post owner can delete their post.",
     *     operationId="deletePost",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Post deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - User does not have the required role or is not the owner of the post",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This action is unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Post not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     )
     * )
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);
        $this->postService->destroy($post);
        return $this->successResponse(message: "Post deleted successfully");
    }
}
