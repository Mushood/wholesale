<?php

namespace Tests\Unit;

use Tests\BlogTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogTest extends BlogTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
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
        $this->assertDatabaseMissing('blog_translations', $this->data);

        $this->addCsrfToken();
        $response = $this->post('blog', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $this->assertDatabaseHas('blog_translations', $this->data);

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

        $this->overrideData([
            'title' => 'title_updated',
            'body'  => 'body_updated'
        ]);
        $this->addCsrfToken();
        $response = $this->put('/blog/' . $this->blog->id , $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $this->assertDatabaseHas('blog_translations', $this->data);

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
