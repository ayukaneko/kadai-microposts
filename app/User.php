<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
      /**
     * このユーザが所有する投稿。（ Micropostモデルとの関係を定義）
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
       /**
     * このユーザがフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'user_favorites', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    /**
     * このユーザをフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

     /**
     * このユーザに関係するモデルの件数をロードする。
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount('microposts', 'followings', 'followers','favorites');
    }
      /**
     * $userIdで指定されたユーザをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // すでにフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }

    /**
     * $userIdで指定されたユーザをアンフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function unfollow($userId)
    {
    
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // すでにフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    
    
    public function unfavorite($micropostId)
    {
        // すでにfavoriteしているかの確認
        $exist = $this->is_favorite($micropostId);
        // 対象が自分自身かどうかの確認
        // $its_me = $this->id == $micropostId;

        if ($exist) {
            // すでにfavoriteしていればfavoriteを外す
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            // 未favoriteであれば何もしない
            return false;
        }
    }
    
    
    
    
    public function favorite($micropostId)
    {
        // すでにfavoriteしているかの確認
        $exist = $this->is_favorite($micropostId);
        // 対象が自分自身かどうかの確認
        // $its_me = $this->id == $micropostId;

        if ($exist) {
            // すでにfavoriteしていれば何もしない
            return false;
        } else {
            // 未favoriteであればfavoriteする
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
      public function favorites_microposts()
    {
        
        // このユーザがお気に入り中のmicropostのidを取得して配列にする
        $micropostIds = $this->favorites()->pluck('user_favorites.micropost_id')->toArray();
        // それらのidが投稿に絞り込む
        return Micropost::whereIn('id', $micropostIds);
       
    }
      public function unfavorites_microposts()
    {
        // このユーザがお気に入り中のmicropostのidを取得して配列にする
        $micropostIds = $this->favorites()->pluck('user_favorites.micropost_id')->toArray();
        // それらのidが投稿に絞り込む
        return Micropost::whereIn('id', $micropostIds);
        
       
    }
    
    
    
    
    
    
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
        
     public function is_following($userId)
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    
     public function is_favorite($micropostid)
    {
       
        return $this->favorites()->where('micropost_id', $micropostid)->exists();
    }
}
