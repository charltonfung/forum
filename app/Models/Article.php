<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'asc');
    }

    public function likes()
    {
        return $this->hasMany(ArticleLike::class);
    }

    /**
     * 給定使用者是否讚過這篇文章
     */
    public function likedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    protected $fillable = ['title', 'content'];
}
