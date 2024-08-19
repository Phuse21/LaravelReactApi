<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();

        //format posts with api resource collection
        $formattedPosts = PostResource::collection($posts);

        //return formatted posts with api helper

        return ApiHelper::response($formattedPosts, 'Posts fetched successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validation
        $fields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $post = $request->user()->posts()->create($fields);

        //format post with post resource

        // return ['post' => $post, 'user' => $post->user];

        $formatPost = new PostResource($post);

        return ApiHelper::response($formatPost, 'Post created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $formatPost = new PostResource($post);
        return ApiHelper::response($formatPost, 'Post fetched successfully', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('modify', $post);
        //validation
        $fields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $post->update($fields);

        //format post with post resource

        $formatPost = new PostResource($post);

        return ApiHelper::response($formatPost, 'Post updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);

        $post->delete();

        return ApiHelper::response(null, 'Post deleted successfully', 200);
    }
}