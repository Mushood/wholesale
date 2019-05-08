<?php

namespace Tests\Unit;

use Tests\BlogTestCase;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogJSONTest extends BlogTestCase
{
    use DatabaseTransactions;

    protected $structure;

    public function setUp() : void
    {
        parent::setUp();

        $this->structure = [
            'data' => [
                'id', 'title', 'subtitle', 'introduction' , 'body' , 'views' , 'author',
            ]
        ];
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
        $this->assertDatabaseMissing('blog_translations', $this->data);

        $this->addCsrfToken();
        $response = $this->withHeaders([
            'Accept' => 'Application/json',
        ])->post('blog', $this->data);

        $response->assertStatus(Response::HTTP_CREATED);

        unset($this->data['_token']);
        $this->assertDatabaseHas('blog_translations', $this->data);

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

        $this->overrideData([
            'title' => 'title_updated',
            'body'  => 'body_updated'
        ]);
        $this->addCsrfToken();
        $response = $this->withHeaders([
            'Accept' => 'Application/json',
        ])->put('/blog/' . $this->blog->id , $this->data);

        $response->assertStatus(Response::HTTP_OK);

        unset($this->data['_token']);
        $this->assertDatabaseHas('blog_translations', $this->data);

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
