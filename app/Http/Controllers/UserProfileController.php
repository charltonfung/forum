<?php

namespace App\Http\Controllers;

/**
 * 個人中心 Controller（single-action）
 * ============================================================
 * 對應 GET /user_profile：顯示登入者自己的文章列表 + 基本資料。
 *
 * Laravel 對「只做一件事」的 controller 有 single-action 慣例：
 *   - 類別只實作一個 __invoke 方法
 *   - 路由不寫 method 名，直接指向 controller class
 *
 * 比起取個假名（例如 show / index）更語意清楚。
 */
class UserProfileController extends Controller
{
    public function __invoke()
    {
        // withCount 讓每篇文章帶 likes_count / comments_count，避免 view 內 N+1
        $articles = auth()->user()
            ->articles()
            ->withCount(['likes', 'comments'])
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('user_profile', ['articles' => $articles]);
    }
}
