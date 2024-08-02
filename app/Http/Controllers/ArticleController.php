<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Start store article');
        $request->validate([
            'author' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        try {
            $article = Article::create([
                'author' => $request->input('author'),
                'title' => $request->input('title'),
                'body' => $request->input('body'),
            ]);
            Log::info('Article Response', ['response', $article]);

            Cache::flush();
            
            return response()->json([
                'message' => 'Article created successfully',
                'article' => $article
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create article',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function index(Request $request)
    {
        Log::info('Start get articles');
        $cacheKey = 'articles_' . md5($request->fullUrl());

        try {
            $articles = Cache::get($cacheKey);
            Log::info('Article from cache', ['articles' => $articles]);

            if ($articles) {
                return response()->json(Cache::get($cacheKey));
            }

            $query = Article::query();

            if ($request->has('query')) {
                $query->where('body', 'like', "%{$request->query('query')}%")
                    ->orWhere('title', 'like', "%{$request->query('query')}%");
            }

            if ($request->has('author')) {
                $query->where('author', $request->query('author'));
            }
            Log::info('SQL Query:', ['query' => $query->toSql()]);

            $articles = $query->orderBy('created_at', 'desc')->get();
            Log::info('Article from DB', ['articles', $articles]);

            Cache::put($cacheKey, $articles, now()->addMinutes(10));

            return response()->json($articles);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
