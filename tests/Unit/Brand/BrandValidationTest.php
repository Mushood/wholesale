<?php

namespace Tests\Unit;

use Tests\BrandTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BrandValidationTest extends BrandTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();

        $this->overrideData([
            'category_id'    => null
        ]);
    }

    /**
     * Store new brand
     */
    public function testBrandRequiresTitle()
    {

        $this->assertDatabaseMissing('brands', $this->data);

        $this->addCSRFTokenAndUnsetField('title');
        $response = $this->post('brand', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        //only one field in data
        /*$this->removeCsrfToken();
        $this->assertDatabaseMissing('brands', $this->data);*/

        $response->assertSee('Redirecting');
    }

    /**
     * Store new brand
     */
    public function testBrandRequiresCsrfToken()
    {
        $this->assertDatabaseMissing('brands', $this->data);

        $response = $this->post('brand', $this->data);

        $response->assertStatus(419);

        $this->assertDatabaseMissing('brands', $this->data);

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
