<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\BlogTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
        $response = $this->getJsonRequest()->get('/blog');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(Controller::PAGINATION, 'data');
    }

    /**
     * Api to store new blog
     */
    public function testUserCanCreateNewBlogJSON()
    {
        Storage::fake('public');
        $this->assertDatabaseMissing('blog_translations', $this->data);

        $this->addCsrfToken();

        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->getJsonRequest()->post('blog', $this->data);

        $response->assertStatus(Response::HTTP_CREATED);

        $blog = BlogTranslation::where('title', $this->data['title'])->first()->blog;
        unset($this->data['_token']);
        unset($this->data['image']);
        $this->assertDatabaseHas('blog_translations', $this->data);
        Storage::disk('public')->assertExists(
            $blog->getMedia()[0]->id . '/' . $blog->getMedia()[0]->file_name
        );
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to retrieve the blog
     */
    public function testUserCanRetrieveABlogJSON()
    {
        $response   = $this->getJsonRequest()->get('/blog/' . $this->blog->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to update the blog
     */
    public function testUserCanUpdateBlogJSON()
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
        $response = $this->getJsonRequest()->put('/blog/' . $this->blog->id , $this->data);

        $response->assertStatus(Response::HTTP_OK);

        unset($this->data['_token']);
        unset($this->data['image']);
        $this->assertDatabaseHas('blog_translations', $this->data);
        Storage::disk('public')->assertExists(
            $this->blog->getMedia()[0]->id . '/' . $this->blog->getMedia()[0]->file_name
        );
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

        $response   = $this->getJsonRequest()->delete('/blog/' . $this->blog->id, ['_token' => csrf_token()]);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('blog_translations', [
            'title' => $this->blog->title,
            'body'  => $this->blog->body
        ]);
    }
}
