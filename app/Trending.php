<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    protected function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }

    public function get()
    {
        return collect(Redis::zrevrange($this->cacheKey(), 0, 4))->map(function ($thread) {
            return json_decode($thread);
        });
    }

    public function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}
