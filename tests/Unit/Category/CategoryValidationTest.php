<?php

namespace Tests\Unit;

use Tests\CategoryTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryValidationTest extends CategoryTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Store new category
     */
    public function testCategoryRequiresTitle()
    {
        unset($this->data['type']);
        $this->assertDatabaseMissing('category_translations', $this->data);

        $this->addCSRFTokenAndUnsetField('title');
        $response = $this->post('category', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $this->assertDatabaseMissing('category_translations', $this->data);

        $response->assertSee('Redirecting');
    }

    /**
     * Store new category
     */
    public function testCategoryRequiresCsrfToken()
    {
        unset($this->data['type']);
        $this->assertDatabaseMissing('category_translations', $this->data);

        $response = $this->post('category', $this->data);

        $response->assertStatus(419);

        $this->assertDatabaseMissing('category_translations', $this->data);

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
