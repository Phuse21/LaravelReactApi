<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Http\Resources\ApiResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();

        //format posts with api resource collection
        $formattedPosts = ApiResource::collection($posts);

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

        $post = Post::create($fields);

        return $post;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $formatPost = new ApiResource($post);
        return ApiHelper::response($formatPost, 'Post fetched successfully', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //validation
        $fields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $post->update($fields);

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return [
            'message' => 'Post deleted successfully'
        ];
    }
}