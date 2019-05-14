<?php

namespace Tests\Unit;

use App\Models\Blog;
use Tests\BlogTestCase;
use Illuminate\Http\Response;
use App\Models\BlogTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        Storage::fake('public');
        $this->overrideData([
            'category_id'    => null
        ]);
        $this->assertDatabaseMissing('blog_translations', $this->data);

        $this->addCsrfToken();
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('blog', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $blogT  = BlogTranslation::where('title', $this->data['title'])->first();
        $blog   = Blog::withoutGlobalScopes()->find($blogT->blog_id);
        $this->overrideData([
            'image'    => null
        ]);
        $this->assertDatabaseHas('blog_translations', $this->data);
        Storage::disk('public')->assertExists(
            $blog->getMedia()[0]->id . '/' . $blog->getMedia()[0]->file_name
        );

        $response->assertSee('Redirecting');
    }

    /**
     * Retrieve a blog
     */
    public function testUserCanRetrieveABlog()
    {
        $this->withoutExceptionHandling();
        $response   = $this->get('/blog/' . $this->blog->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($this->blog->title);
    }

    /**
     * Access Edit Page
     */
    public function testUserCanAccessEditPage()
    {
        $response = $this->get("/blog/{$this->blog->id}/edit");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Create Blog');
    }

    /**
     * Update the blog
     */
    public function testUserCanUpdateBlog()
    {
        Storage::fake('public');
        $this->assertDatabaseHas('blog_translations', [
            'title' => $this->blog->title,
            'body'  => $this->blog->body
        ]);

        $this->overrideData([
            'title' => 'title_updated',
            'body'  => 'body_updated',
            'image' => UploadedFile::fake()->image('test1.jpg')
        ]);
        $this->addCsrfToken();
        $response = $this->put('/blog/' . $this->blog->id , $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $this->overrideData([
            'image'          => null,
            'category_id'    => null
        ]);
        $this->assertDatabaseHas('blog_translations', $this->data);
        Storage::disk('public')->assertExists(
            $this->blog->getMedia()[0]->id . '/' . $this->blog->getMedia()[0]->file_name
        );

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
