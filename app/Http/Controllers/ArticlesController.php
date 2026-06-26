<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }
    public function index()
    {
        // withCount 讓每篇文章帶 likes_count / comments_count，避免 view 內 N+1
        $articles = Article::with('user')
            ->withCount(['likes', 'comments'])
            ->orderBy('id', 'desc')
            ->paginate(20);
        return view('articles.index', ['articles' => $articles]);
    }

    public function show($id)
    {
        $article = Article::find($id);
        return view('articles.show', ['article' => $article]);
    }

    public function create()
    {
        return view('articles.create');
    }
    
    public function store(Request $request)
    {
        $content = $request->validate([
            'title' => 'required',
            'content' => 'required|min:10',
        ]);

        auth()->user()->articles()->create($content);
        return redirect()->route('root')->with('notice', '文章發表成功！');
    }

    public function edit($id)
    {
        $article = auth()->user()->articles->find($id);
        return view('articles.edit', ['article' => $article]);
    }

    public function update(Request $request, $id)
    {
        $article = auth()->user()->articles->find($id);

        $content = $request->validate([
            'title' => 'required',
            'content' => 'required|min:10',
        ]);

        $article->update($content);
        return redirect()->route('root')->with('notice', '文章更新成功！');
    }

    public function destroy($id)
    {
        $article = auth()->user()->articles->find($id);
        $article->delete();
        return redirect()->route('root')->with('notice', '文章已刪除！');
    }
}
