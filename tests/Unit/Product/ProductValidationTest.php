<?php

namespace Tests\Unit;

use Tests\ProductTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductValidationTest extends ProductTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();

        $this->overrideData([
            'category_id'   => null,
            'brand_id'      => null,
            'shop_id'       => null,
            'min_quantity'  => null,
            'measure'       => null,
        ]);
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
}
