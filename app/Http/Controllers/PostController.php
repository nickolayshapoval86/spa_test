<?php

namespace App\Http\Controllers;

use App\Http\Services\RedditService;
use App\Post;

class PostController extends Controller
{
    private function _loadPosts()
    {
        $redditService = new RedditService();

        try {
            $posts = $redditService->loadPosts();
            return response()->json($posts);
        }catch(\ErrorException $e) {
            return response('Error occured', 400);
        }
    }
    public function getAll()
    {
        return $this->_loadPosts();
    }

    public function delete($id){
        try {
            $post = Post::query()->find($id);
            $post->is_deleted = true;
            $post->save();
        }catch(\ErrorException $e) {
            return response('Error occured', 400);
        }

        return $this->_loadPosts();
    }
}
