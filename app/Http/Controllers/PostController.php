<?php

namespace App\Http\Controllers;

use App\Http\Services\RedditService;
use App\Post;

class PostController extends Controller
{
    private function _loadPosts()
    {
        $postsLimit = 15;
        $redditService = new RedditService();

        try {
            $redditService->loadPosts();

            $posts = Post::where('is_deleted', false)
                ->select(['id', 'headline', 'content', 'user_id', 'reddit_id'])
                ->orderBy('id', 'desc')
                ->with('user')
                ->simplePaginate($postsLimit);

            if (count($posts) === $postsLimit) {
                return response()->json($posts);
            }

            $post = Post::select(['reddit_id'])
                ->orderBy('id', 'desc')
                ->first();
            if(!$after = $post->reddit_id) {
                throw new \App\Exceptions\Exception('Error');
            }
            $redditService->loadPosts($after);

            $posts = Post::where('is_deleted', false)
                ->select(['id', 'headline', 'content', 'user_id', 'reddit_id'])
                ->orderBy('id', 'desc')
                ->with('user')
                ->simplePaginate($postsLimit);

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
            $post = Post::find($id);
            $post->is_deleted = true;
            $post->save();
        }catch(\ErrorException $e) {
            return response('Error occured', 400);
        }

        return $this->_loadPosts();
    }
}