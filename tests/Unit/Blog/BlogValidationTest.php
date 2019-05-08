<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Blog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogValidationTest extends TestCase
{
    use DatabaseTransactions;

    protected $blog;

    protected $blogData;

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
     * Store new blog
     */
    public function testBlogRequiresTitle()
    {
        $this->assertDatabaseMissing('blog_translations', $this->blogData);

        $this->addCSRFTokenAndUnsetField('title');
        $response = $this->post('blog', $this->blogData);

        $response->assertStatus(Response::HTTP_FOUND);

        unset($this->blogData['_token']);
        $this->assertDatabaseMissing('blog_translations', $this->blogData);

        $response->assertSee('Redirecting');
    }

    /**
     * Store new blog
     */
    public function testBlogRequiresBody()
    {
        $this->assertDatabaseMissing('blog_translations', $this->blogData);

        $this->addCSRFTokenAndUnsetField('body');
        $response = $this->post('blog', $this->blogData);

        $response->assertStatus(Response::HTTP_FOUND);

        unset($this->blogData['_token']);
        $this->assertDatabaseMissing('blog_translations', $this->blogData);

        $response->assertSee('Redirecting');
    }

    /**
     * Store new blog
     */
    public function testBlogRequiresCsrfToken()
    {
        $this->assertDatabaseMissing('blog_translations', $this->blogData);

        $response = $this->post('blog', $this->blogData);

        $response->assertStatus(419);

        $this->assertDatabaseMissing('blog_translations', $this->blogData);

        $response->assertSee('Page Expired');
    }

    private function addCSRFTokenAndUnsetField(String $unset)
    {
        unset($this->blogData[$unset]);
        $this->blogData['_token'] = csrf_token();
    }
}
