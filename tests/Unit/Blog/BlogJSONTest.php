<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Blog;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogJSONTest extends TestCase
{
    use DatabaseTransactions;

    protected $blog;

    protected $blogData;

    protected $structure;

    public function setUp() : void
    {
        parent::setUp();

        $this->blog = Blog::first();

        $this->blogData = [
            'title'         => 'title',
            'body'          => 'body',
            'subtitle'      => 'subtitle',
            'introduction'  => 'introduction'
        ];

        $this->structure = [
            'data' => [
                'id', 'title', 'subtitle', 'introduction' , 'body' , 'views' , 'author',
            ]
        ];

        Session::start();
    }

    /**
     * Retrieve all blogs
     *
     * @return void
     */
    public function testUserCanRetrieveAllBlogsJSON()
    {
        $response = $this->withHeaders([
            'Accept' => 'Application/json',
        ])->get('/blog');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(Controller::PAGINATION, 'data');
    }

    /**
     * Api to store new blog
     */
    public function testUserCanCreateNewBlogJSON()
    {
        $this->assertDatabaseMissing('blog_translations', $this->blogData);

        $this->blogData['_token'] = csrf_token();
        $response = $this->withHeaders([
            'Accept' => 'Application/json',
        ])->post('blog', $this->blogData);

        $response->assertStatus(Response::HTTP_CREATED);

        unset($this->blogData['_token']);
        $this->assertDatabaseHas('blog_translations', $this->blogData);

        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to retrieve the blog
     */
    public function testUserCanRetrieveABlogJSON()
    {
        $response   = $this->withHeaders([
            'Accept' => 'Application/json',
        ])->get('/blog/' . $this->blog->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to update the blog
     */
    public function testUserCanUpdateBlogJSON()
    {
        $this->assertDatabaseHas('blog_translations', [
            'title' => $this->blog->title,
            'body'  => $this->blog->body
        ]);

        $this->blogData['title'] = 'title_updated';
        $this->blogData['body'] = 'body_updated';
        $this->blogData['_token'] = csrf_token();
        $response = $this->withHeaders([
            'Accept' => 'Application/json',
        ])->put('/blog/' . $this->blog->id , $this->blogData);

        $response->assertStatus(Response::HTTP_OK);

        unset($this->blogData['_token']);
        $this->assertDatabaseHas('blog_translations', $this->blogData);

        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to delete a blog
     */
    public function testUserCanDeleteABlogJSON()
    {
        $this->assertDatabaseHas('blog_translations', [
            'title' => $this->blog->title,
            'body'  => $this->blog->body
        ]);

        $response   = $this->withHeaders([
            'Accept' => 'Application/json',
        ])->delete('/blog/' . $this->blog->id, ['_token' => csrf_token()]);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('blog_translations', [
            'title' => $this->blog->title,
            'body'  => $this->blog->body
        ]);
    }
}
