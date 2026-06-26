<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['content', 'article_id', 'user_id'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * 給定使用者是否讚過這則留言（用於 view 內 @if 判斷）
     */
    public function likedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
