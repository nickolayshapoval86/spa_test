<?php

namespace App\Http\Services;

use App\Post;
use App\User;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class RedditService
{
    /** @var Client */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function request($after = null): array
    {
        $apiUrl = config('reddit.api_url');
        $requestLimit = config('reddit.request_limit');
        $requestParams = ['limit' => $requestLimit];
        if($after) {
            $requestParams['after'] = $after;
        }
        $request_url = $apiUrl . '?' . http_build_query($requestParams);

        $response = $this->client->request('GET', $request_url, [
            'headers' => [
                'User-Agent' => 'Reddit parse/1.0',
            ]
        ]);

//        echo $response->getStatusCode(); // 200
//        echo $response->getHeaderLine('content-type'); // 'application/json; charset=utf8'
        return json_decode($response->getBody(), true); // '{"id": 1420053, "name": "guzzle", ...}'
    }

    public function loadPosts($after = null)
    {
        $posts = $this->request($after);

        $items = $posts['data']['children'];

        foreach ($items as $item) {
            $user = User::firstOrCreate(['username' => $item['data']['author']]);
            $content = '';
            if(!empty($item['data']['description'])) {
                $content = $item['data']['description'];
            }
            $post = Post::firstOrCreate([
                'reddit_id' => $item['data']['name'],
            ], [
                'headline' => $item['data']['title'],
                'content' => $content,
                'user_id' => $user->id,
                'is_deleted' => false,
            ]);
        }
    }
}
