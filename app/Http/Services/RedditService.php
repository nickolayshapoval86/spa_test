<?php

namespace App\Http\Services;

use App\Post;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class RedditService
{
    /** @var Client */
    private $client;

    /** @var string */
    private $lastRedditId;

    /** @var int */
    private $postsLimit;

    public function __construct()
    {
        $this->client = new Client();
        $this->postsLimit = config('reddit.request_limit');
    }

    public function request(): array
    {
        $apiUrl = config('reddit.api_url');
        $requestParams = ['limit' => $this->postsLimit];
        if($this->lastRedditId) {
            $requestParams['after'] = $this->lastRedditId;
        }
        $request_url = $apiUrl . '?' . http_build_query($requestParams);

        $response = $this->client->request('GET', $request_url, [
            'headers' => [
                'User-Agent' => 'Reddit parse/1.0',
            ]
        ]);

        return json_decode($response->getBody(), true); // '{"id": 1420053, "name": "guzzle", ...}'
    }

    public function loadFresh()
    {
        $posts = $this->request();

        $items = $posts['data']['children'];

        foreach ($items as $item) {
            $user = User::query()->firstOrCreate(['username' => $item['data']['author']]);
            $content = '';
            if(!empty($item['data']['description'])) {
                $content = $item['data']['description'];
            }
            Post::query()->firstOrCreate(
                [
                    'reddit_id' => $item['data']['name'],
                ],
                [
                    'headline' => $item['data']['title'],
                    'content' => $content,
                    'user_id' => $user->id,
                    'is_deleted' => false,
                ]
            );
        }
    }

    public function loadPosts(): Paginator
    {
        $posts = $this->getPosts();
        if (!$posts->count()) {
            $this->loadFresh();
        }

        if (!$this->isLoadFresh($posts = $this->getPosts())) {
            return $posts;
        }

        $this->setLastRedditId();
        if(!$this->getLastRedditId()) {
            throw new \App\Exceptions\Exception('Error');
        }

        $this->loadFresh();

        return $this->getPosts();
    }

    private function getPosts(): Paginator
    {
        return $this->getPostsBuilder()->simplePaginate($this->postsLimit);
    }

    private function isLoadFresh($posts): bool
    {

        return $posts->count() !== $this->postsLimit;
    }

    private function getPostsBuilder(): Builder
    {
        return Post::query()
            ->select(['id', 'headline', 'content', 'user_id', 'reddit_id'])
            ->where('is_deleted', false)
            ->orderBy('id', 'desc')
            ->with('user')
        ;
    }

    private function setLastRedditId(): void
    {
        $post = Post::query()
            ->select(['reddit_id'])
            ->orderBy('id', 'desc')
            ->first()
        ;

        $this->lastRedditId = $post->reddit_id;
    }

    private function getLastRedditId(): string
    {
        return $this->lastRedditId;
    }
}
