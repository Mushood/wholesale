<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Blog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogTest extends TestCase
{
    use DatabaseTransactions;

    protected $blog;

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

        Session::start();
    }

    /**
     * Retrieve all blogs
     *
     * @return void
     */
    public function testUserCanRetrieveAllBlogs()
    {
        $response = $this->get('/blog');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('All Blogs');
    }

    /**
     * Access Create Page
     */
    public function testUserCanAccessCreatePage()
    {
        $response = $this->get('/blog/create');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Create Blog');
    }

    /**
     * Store new blog
     */
    public function testUserCanCreateNewBlog()
    {
        $this->assertDatabaseMissing('blog_translations', $this->blogData);

        $this->blogData['_token'] = csrf_token();
        $response = $this->post('blog', $this->blogData);

        $response->assertStatus(Response::HTTP_FOUND);

        unset($this->blogData['_token']);
        $this->assertDatabaseHas('blog_translations', $this->blogData);

        $response->assertSee('Redirecting');
    }

    /**
     * Retrieve a blog
     */
    public function testUserCanRetrieveABlog()
    {
        $response   = $this->get('/blog/' . $this->blog->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($this->blog->title);
    }

    /**
     * Update the blog
     */
    public function testUserCanUpdateBlog()
    {
        $this->assertDatabaseHas('blog_translations', [
            'title' => $this->blog->title,
            'body'  => $this->blog->body
        ]);

        $this->blogData['title'] = 'title_updated';
        $this->blogData['body'] = 'body_updated';
        $this->blogData['_token'] = csrf_token();
        $response = $this->put('/blog/' . $this->blog->id , $this->blogData);

        $response->assertStatus(Response::HTTP_FOUND);

        unset($this->blogData['_token']);
        $this->assertDatabaseHas('blog_translations', $this->blogData);

        $response->assertSee('Redirecting');
    }

    /**
     * Delete a blog
     */
    public function testUserCanDeleteABlog()
    {
        $this->assertDatabaseHas('blog_translations', [
            'title' => $this->blog->title,
            'body'  => $this->blog->body
        ]);

        $response   = $this->delete('/blog/' . $this->blog->id, ['_token' => csrf_token()]);

        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseMissing('blog_translations', [
            'title' => $this->blog->title,
            'body'  => $this->blog->body
        ]);
    }
}
