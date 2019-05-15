<?php

namespace Tests\Unit;

use App\Models\Shop;
use Tests\ShopTestCase;
use Illuminate\Http\Response;
use App\Models\ShopTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShopTest extends ShopTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Retrieve all shops
     *
     * @return void
     */
    public function testUserCanRetrieveAllShops()
    {
        $response = $this->get('/shop');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('All Shops');
    }

    /**
     * Access Create Page
     */
    public function testUserCanAccessCreatePage()
    {
        $response = $this->get('/shop/create');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Create Shop');
    }

    /**
     * Store new shop
     */
    public function testUserCanCreateNewShop()
    {
        Storage::fake('public');
        $this->assertDatabaseMissing('shops', $this->data);

        $this->addCsrfToken();
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('shop', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $shop   = Shop::withoutGlobalScopes()->where('title', $this->data['title'])->first();
        $this->overrideData([
            'image'    => null
        ]);
        $this->assertDatabaseHas('shops', $this->data);
        Storage::disk('public')->assertExists(
            $shop->getMedia()[0]->id . '/' . $shop->getMedia()[0]->file_name
        );

        $response->assertSee('Redirecting');
    }

    /**
     * Retrieve a shop
     */
    public function testUserCanRetrieveAShop()
    {
        $this->withoutExceptionHandling();
        $response   = $this->get('/shop/' . $this->shop->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($this->shop->title);
    }

    /**
     * Access Edit Page
     */
    public function testUserCanAccessEditPage()
    {
        $response = $this->get("/shop/{$this->shop->id}/edit");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Create Shop');
    }

    /**
     * Update the shop
     */
    public function testUserCanUpdateShop()
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
        $response = $this->put('/shop/' . $this->shop->id , $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $this->overrideData([
            'image'          => null,
        ]);
        $this->assertDatabaseHas('shops', $this->data);
        Storage::disk('public')->assertExists(
            $this->shop->getMedia()[0]->id . '/' . $this->shop->getMedia()[0]->file_name
        );

        $response->assertSee('Redirecting');
    }

    /**
     * Delete a shop
     */
    public function testUserCanDeleteAShop()
    {
        $title = "unique_title";
        $this->shop->title = $title;
        $this->shop->save();

        $this->assertDatabaseHas('shops', [
            'id'            => $this->shop->id,
            'title'         => $title,
            'deleted_at'    => null,
        ]);

        $response   = $this->delete('/shop/' . $this->shop->id, ['_token' => csrf_token()]);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing('shops', [
            'id'            => $this->shop->id,
            'title'         => $title,
            'deleted_at'    => null,
        ]);
    }
}
