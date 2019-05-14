<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\BlogTranslation;
use App\Models\User;
use Tests\BlogTestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
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
        $newBlog = Blog::withoutGlobalScopes()->find($content['data']['id']);

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

    public function testUsersUnauthenticatedCannotSeeUnpublishedBlogs()
    {
        $data = [
            'title' => 'published',
            'body'  => 'published',
        ];
        $this->assertDatabaseMissing('blog_translations', $data);

        Blog::create($data);

        $this->assertDatabaseHas('blog_translations', $data);

        //Reload blogs to clear global scope
        Blog::clearBootedModels();

        $response = $this->get('/blog');
        $response->assertDontSee($data['title']);
    }

    public function testUsersNotAdminCannotSeeUnpublishedBlogs()
    {
        $data = [
            'title' => 'published',
            'body'  => 'published',
        ];
        $this->assertDatabaseMissing('blog_translations', $data);

        Blog::create($data);

        $this->assertDatabaseHas('blog_translations', $data);

        //Reload blogs to clear global scope
        Blog::clearBootedModels();

        $user = User::where('email', User::WHOLESALER_EMAIL)->first();
        $response = $this->actingAs($user)->get('/blog');
        $response->assertDontSee($data['title']);
    }

    public function testUsersAdminCanSeeUnpublishedBlogs()
    {
        $this->assertDatabaseMissing('blog_translations', $this->data);

        $this->addCsrfToken();
        $this->overrideData([
            'title' => 'published',
            'body'  => 'published',
        ]);
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('blog', $this->data);

        $this->removeCsrfToken();
        unset($this->data['image']);
        $this->assertDatabaseHas('blog_translations', $this->data);

        //Reload blogs to clear global scope
        Blog::clearBootedModels();

        $user = User::where('email', User::ADMIN_EMAIL)->first();
        $response = $this->actingAs($user)->get('/blog?page=1');
        $response->assertSee($this->data['title']);
    }
}
