<?php

namespace Modules\Post\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Post\DTOs\CreatePostDto;
use Modules\Post\Http\Requests\StorePostRequest;
use Modules\Post\Services\PostService;
use Modules\Post\Transformers\PostResource;

class PostController extends Controller
{
    public function __construct(protected PostService $postService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('post::index');
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
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
     *     )
     * )
     */
    public function store(StorePostRequest $request)
    {
        $dto = CreatePostDto::fromArray(
            data: $request->validated(),
            user_id: auth('api')->id()
        );

        $post = $this->postService->store($dto);
        $post = PostResource::make($post);

        return $this->successResponse($post);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('post::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('post::edit');
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
