<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\User;
use Tests\ProductTestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductPublishableTest extends ProductTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Store new product
     */
    public function testProductNotPublishedByDefault()
    {
        $this->addCsrfToken();
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->getJsonRequest()->post('product', $this->data);

        $content = json_decode($response->getContent(), true);
        $newProduct = Product::withoutGlobalScopes()->latest()->first();

        $this->assertEquals(false, $newProduct->published);
    }

    public function testProductCanBePublished()
    {
        $response   = $this->get('/product/publish/' . $this->product->id);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals(true, $this->product->fresh()->published);

        $response   = $this->get('/product/unpublish/' . $this->product->id);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals(false, $this->product->fresh()->published);
    }

    public function testProductCanBePublishedJSON()
    {
        $response   = $this->getJsonRequest()->get('/product/publish/' . $this->product->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(true, $this->product->fresh()->published);

        $response   = $this->getJsonRequest()->get('/product/unpublish/' . $this->product->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(false, $this->product->fresh()->published);
    }

    public function testUsersUnauthenticatedCannotSeeUnpublishedProducts()
    {
        $data = [
            'title' => 'published',
            'body'  => 'published',
        ];
        $this->assertDatabaseMissing('product_translations', $data);

        Product::create($data);

        $this->assertDatabaseHas('product_translations', $data);

        //Reload products to clear global scope
        Product::clearBootedModels();

        $response = $this->get('/product');
        $response->assertDontSee($data['title']);
    }

    public function testUsersNotAdminCannotSeeUnpublishedProducts()
    {
        $data = [
            'title' => 'published',
            'body'  => 'published',
        ];
        $this->assertDatabaseMissing('product_translations', $data);

        Product::create($data);

        $this->assertDatabaseHas('product_translations', $data);

        //Reload products to clear global scope
        Product::clearBootedModels();

        $user = User::where('email', User::WHOLESALER_EMAIL)->first();
        $response = $this->actingAs($user)->get('/product');
        $response->assertDontSee($data['title']);
    }

    public function testUsersAdminCanSeeUnpublishedProducts()
    {
        $this->overrideData([
            'category_id'   => null,
            'brand_id'      => null,
            'shop_id'       => null,
            'min_quantity'  => null,
            'measure'       => null,
        ]);
        $this->assertDatabaseMissing('product_translations', $this->data);

        $this->addCsrfToken();
        $this->overrideData([
            'title' => 'published',
            'body'  => 'published',
        ]);
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('product', $this->data);

        $this->removeCsrfToken();
        unset($this->data['image']);
        $this->assertDatabaseHas('product_translations', $this->data);

        //Reload products to clear global scope
        Product::clearBootedModels();

        $user = User::where('email', User::ADMIN_EMAIL)->first();
        $response = $this->actingAs($user)->get('/product?page=1');
        $response->assertSee($this->data['title']);
    }
}
