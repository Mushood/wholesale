<?php

namespace Tests\Unit;

use Tests\BlogTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BlogValidationTest extends BlogTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Store new blog
     */
    public function testBlogRequiresTitle()
    {
        $this->assertDatabaseMissing('blog_translations', $this->data);

        $this->addCSRFTokenAndUnsetField('title');
        $response = $this->post('blog', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $this->assertDatabaseMissing('blog_translations', $this->data);

        $response->assertSee('Redirecting');
    }

    /**
     * Store new blog
     */
    public function testBlogRequiresBody()
    {
        $this->assertDatabaseMissing('blog_translations', $this->data);

        $this->addCSRFTokenAndUnsetField('body');
        $response = $this->post('blog', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $this->assertDatabaseMissing('blog_translations', $this->data);

        $response->assertSee('Redirecting');
    }

    /**
     * Store new blog
     */
    public function testBlogRequiresCsrfToken()
    {
        $this->assertDatabaseMissing('blog_translations', $this->data);

        $response = $this->post('blog', $this->data);

        $response->assertStatus(419);

        $this->assertDatabaseMissing('blog_translations', $this->data);

        $response->assertSee('Page Expired');
    }

    protected function addCSRFTokenAndUnsetField(String $unset)
    {
        $this->overrideData([ $unset => null]);
        $this->addCsrfToken();
    }
    #TODO
    /**
     * Policy
     * Add Author
     */
}
