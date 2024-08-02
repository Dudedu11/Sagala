<?php

namespace Tests\Feature;

use App\Models\Article;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /** @test */
    public function store()
    {
        $response = $this->postJson('/api/articles', [
            'author' => 'Muhamad Rafli Nur Ikhsan',
            'title' => 'Test Article',
            'body' => 'This is the body',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Article created successfully',
                'article' => [
                    'author' => 'Muhamad Rafli Nur Ikhsan',
                    'title' => 'Test Article',
                    'body' => 'This is the body',
                ]
            ]);

        $this->assertDatabaseHas('articles', [
            'author' => 'Muhamad Rafli Nur Ikhsan',
            'title' => 'Test Article',
            'body' => 'This is the body',
        ]);
    }

     /** @test */
     public function getArticle()
     {
         $article = Article::create([
             'author' => 'Muhamad Rafli Nur Ikhsan',
             'title' => 'Test Article',
             'body' => 'This is the body',
         ]);
 
         $response = $this->getJson('/api/articles');
 
         $response->assertStatus(200)
             ->assertJson([
                 [
                     'author' => 'Muhamad Rafli Nur Ikhsan',
                     'title' => 'Test Article',
                     'body' => 'This is the body',
                 ]
             ]);
 
         $this->assertDatabaseHas('articles', [
             'author' => 'Muhamad Rafli Nur Ikhsan',
             'title' => 'Test Article',
             'body' => 'This is the body',
         ]);
     }
}
