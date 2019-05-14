<?php

namespace Tests\Unit;

use App\Models\Shop;
use Tests\ShopTestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShopJSONTest extends ShopTestCase
{
    use DatabaseTransactions;

    protected $structure;

    public function setUp() : void
    {
        parent::setUp();

        $this->structure = [
            'data' => [
                'title', 'views'
            ]
        ];
    }

    /**
     * Retrieve all shops
     *
     * @return void
     */
    public function testUserCanRetrieveAllShopsJSON()
    {
        $response = $this->getJsonRequest()->get('/shop');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(Controller::PAGINATION, 'data');
    }

    /**
     * Api to store new shop
     */
    public function testUserCanCreateNewShopJSON()
    {
        Storage::fake('public');
        $this->assertDatabaseMissing('shops', $this->data);

        $this->addCsrfToken();

        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->getJsonRequest()->post('shop', $this->data);

        $response->assertStatus(Response::HTTP_CREATED);

        $shop   = Shop::withoutGlobalScopes()->where('title', $this->data['title'])->first();
        $this->removeCsrfToken();
        $this->overrideData([
            'image'          => null,
        ]);
        $this->assertDatabaseHas('shops', $this->data);
        Storage::disk('public')->assertExists(
            $shop->getMedia()[0]->id . '/' . $shop->getMedia()[0]->file_name
        );
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to retrieve the shop
     */
    public function testUserCanRetrieveAShopJSON()
    {
        $response   = $this->getJsonRequest()->get('/shop/' . $this->shop->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to update the shop
     */
    public function testUserCanUpdateShopJSON()
    {
        Storage::fake('public');
        $this->assertDatabaseHas('shops', [
            'title' => $this->shop->title,
        ]);

        $this->overrideData([
            'title' => 'title_updated',
            'image' => UploadedFile::fake()->image('test1.jpg')
        ]);
        $this->addCsrfToken();
        $response = $this->getJsonRequest()->put('/shop/' . $this->shop->id , $this->data);

        $response->assertStatus(Response::HTTP_OK);

        $this->removeCsrfToken();
        $this->overrideData([
            'image'          => null,
        ]);
        $this->assertDatabaseHas('shops', $this->data);
        Storage::disk('public')->assertExists(
            $this->shop->getMedia()[0]->id . '/' . $this->shop->getMedia()[0]->file_name
        );
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to delete a shop
     */
    public function testUserCanDeleteAShopJSON()
    {
        $title = "unique_title";
        $this->shop->title = $title;
        $this->shop->save();

        $this->assertDatabaseHas('shops', [
            'title' => $this->shop->fresh()->title,
        ]);

        $response   = $this->getJsonRequest()->delete('/shop/' . $this->shop->id, ['_token' => csrf_token()]);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('shops', [
            'title' => $title,
        ]);
    }
}
