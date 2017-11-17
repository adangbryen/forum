<?php

namespace App;


trait Favorable
{
    public static function bootFavorable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        if ($this->favorites()->where('user_id', auth()->id())->exists()) return;
        return $this->favorites()->create(['user_id' => auth()->id()]);
    }

    public function unFavorite()
    {
        return $this->favorites()->where(['user_id' => auth()->id()])->get()->each->delete();
    }


    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }


}