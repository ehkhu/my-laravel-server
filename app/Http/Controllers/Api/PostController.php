<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        try {
            $posts = $this->postRepository->all($request);
            return response()->json(['data' => $posts]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
        }
    }

    public function show($id)
    {
        try {
            $post = $this->postRepository->find($id);

            if (!$post) {
                return response()->json(['error' => ['code' => 404, 'message' => 'Post not found']], 404);
            }

            return response()->json(['data' => $post]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // Add validation rules here
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => ['code' => 422, 'message' => 'Validation failed', 'errors' => $validator->errors()]], 422);
            }

            $post = $this->postRepository->create($request->all());

            return response()->json(['data' => $post], 201);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                // Add validation rules here
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => ['code' => 422, 'message' => 'Validation failed', 'errors' => $validator->errors()]], 422);
            }

            $post = $this->postRepository->update($id, $request->all());

            return response()->json(['data' => $post]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->postRepository->delete($id);

            return response()->json(['message' => 'Post deleted successfully']);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
        }
    }

    public function values(){
        try {
            $posts = $this->postRepository->values();
            return response()->json(['data' => $posts]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
        }
    }

    //overview
    public function getCountPost(){
        try {
            $posts = $this->postRepository->countPosts();
            return response()->json(['data' => $posts]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
        }
    }
}