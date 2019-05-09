<?php

namespace Tests\Unit;

use App\Models\Blog;
use Tests\BlogTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogPublishableTest extends BlogTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Store new blog
     */
    public function testBlogNotPublishedByDefault()
    {
        $this->addCsrfToken();
        $response = $this->getJsonRequest()->post('blog', $this->data);

        $content = json_decode($response->getContent(), true);
        $newBlog = Blog::find($content['data']['id']);

        $this->assertEquals(false, $newBlog->published);
    }

    public function testBlogCanBePublished()
    {
        $response   = $this->getJsonRequest()->get('/blog/publish/' . $this->blog->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(true, $this->blog->fresh()->published);

        $response   = $this->getJsonRequest()->get('/blog/unpublish/' . $this->blog->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(false, $this->blog->fresh()->published);
    }
}
