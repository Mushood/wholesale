<?php

namespace Tests\Unit;

use Tests\ShopTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShopValidationTest extends ShopTestCase
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
     * Store new shop
     */
    public function testShopRequiresTitle()
    {

        $this->assertDatabaseMissing('shops', $this->data);

        $this->addCSRFTokenAndUnsetField('title');
        $response = $this->post('shop', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        //only one field in data
        /*$this->removeCsrfToken();
        $this->assertDatabaseMissing('shops', $this->data);*/

        $response->assertSee('Redirecting');
    }

    /**
     * Store new shop
     */
    public function testShopRequiresCsrfToken()
    {
        $this->assertDatabaseMissing('shops', $this->data);

        $response = $this->post('shop', $this->data);

        $response->assertStatus(419);

        $this->assertDatabaseMissing('shops', $this->data);

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
