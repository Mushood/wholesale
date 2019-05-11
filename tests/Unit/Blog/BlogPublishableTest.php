<?php

namespace Tests\Unit;

use App\Models\Blog;
use Illuminate\Http\UploadedFile;
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
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->getJsonRequest()->post('blog', $this->data);

        $content = json_decode($response->getContent(), true);
        $newBlog = Blog::find($content['data']['id']);

        $this->assertEquals(false, $newBlog->published);
    }

    public function testBlogCanBePublished()
    {
        $response   = $this->get('/blog/publish/' . $this->blog->id);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals(true, $this->blog->fresh()->published);

        $response   = $this->get('/blog/unpublish/' . $this->blog->id);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals(false, $this->blog->fresh()->published);
    }

    public function testBlogCanBePublishedJSON()
    {
        $response   = $this->getJsonRequest()->get('/blog/publish/' . $this->blog->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(true, $this->blog->fresh()->published);

        $response   = $this->getJsonRequest()->get('/blog/unpublish/' . $this->blog->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(false, $this->blog->fresh()->published);
    }

    public function testPublishableTraitThrowsExceptionWhenNoModelFound()
    {
        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No Model found');
        $response   = $this->getJsonRequest()->get('/unknown/publish/' . $this->blog->id);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
