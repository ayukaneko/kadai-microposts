<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
     protected $fillable = ['content'];

    /**
     * この投稿を所有するユーザ。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    public function favorites_users()
    {
        return $this->belongsTo(User::class,'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

  public function followings()
    {
        return $this->belongsToMany(User::class, 'favorites', 'user_id', 'favorites_id')->withTimestamps();
    }
}