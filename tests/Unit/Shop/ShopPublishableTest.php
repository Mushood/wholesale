<?php

namespace Tests\Unit;

use App\Models\Shop;
use App\Models\User;
use Tests\ShopTestCase;
use Illuminate\Http\Response;
use App\Models\ShopTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShopPublishableTest extends ShopTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Store new shop
     */
    public function testShopNotPublishedByDefault()
    {
        $this->addCsrfToken();
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->getJsonRequest()->post('shop', $this->data);

        $content = json_decode($response->getContent(), true);
        $newShop = Shop::withoutGlobalScopes()->latest()->first();;

        $this->assertEquals(false, $newShop->published);
    }

    public function testShopCanBePublished()
    {
        $response   = $this->get('/shop/publish/' . $this->shop->id);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals(true, $this->shop->fresh()->published);

        $response   = $this->get('/shop/unpublish/' . $this->shop->id);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals(false, $this->shop->fresh()->published);
    }

    public function testShopCanBePublishedJSON()
    {
        $response   = $this->getJsonRequest()->get('/shop/publish/' . $this->shop->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(true, $this->shop->fresh()->published);

        $response   = $this->getJsonRequest()->get('/shop/unpublish/' . $this->shop->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(false, $this->shop->fresh()->published);
    }

    public function testUsersUnauthenticatedCannotSeeUnpublishedShops()
    {
        $data = [
            'title' => 'published',
            'ref'   => 'ref'
        ];
        $this->assertDatabaseMissing('shops', $data);

        Shop::create($data);

        $this->assertDatabaseHas('shops', $data);

        //Reload shops to clear global scope
        Shop::clearBootedModels();

        $response = $this->get('/shop');
        $response->assertDontSee($data['title']);
    }

    public function testUsersNotAdminCannotSeeUnpublishedShops()
    {
        $data = [
            'title' => 'published',
            'ref'   => 'ref'
        ];
        $this->assertDatabaseMissing('shops', $data);

        Shop::create($data);

        $this->assertDatabaseHas('shops', $data);

        //Reload shops to clear global scope
        Shop::clearBootedModels();

        $user = User::where('email', User::WHOLESALER_EMAIL)->first();
        $response = $this->actingAs($user)->get('/shop');
        $response->assertDontSee($data['title']);
    }

    public function testUsersAdminCanSeeUnpublishedShops()
    {
        $this->assertDatabaseMissing('shops', $this->data);

        $this->addCsrfToken();
        $this->overrideData([
            'title' => 'published',
        ]);
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('shop', $this->data);

        $this->removeCsrfToken();
        unset($this->data['image']);
        $this->assertDatabaseHas('shops', $this->data);

        //Reload shops to clear global scope
        Shop::clearBootedModels();

        $user = User::where('email', User::ADMIN_EMAIL)->first();
        $response = $this->actingAs($user)->get('/shop?page=1');
        $response->assertSee($this->data['title']);
    }
}
