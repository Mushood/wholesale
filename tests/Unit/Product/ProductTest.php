<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\ProductTestCase;
use Illuminate\Http\Response;
use App\Models\ProductTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductTest extends ProductTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Retrieve all products
     *
     * @return void

    public function testUserCanRetrieveAllProducts()
    {
        $response = $this->get('/product');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('All Products');
    }
     */
    /**
     * Access Create Page
     */
    public function testUserCanAccessCreatePage()
    {
        $response = $this->get('/product/create');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Create Product');
    }

    /**
     * Retrieve a product

    public function testUserCanRetrieveAProduct()
    {
        $this->withoutExceptionHandling();
        $response   = $this->get('/product/' . $this->product->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($this->product->title);
    }
     */
    /**
     * Access Edit Page
     */
    public function testUserCanAccessEditPage()
    {
        $response = $this->get("/product/{$this->product->id}/edit");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Create Product');
    }


    /**
     * Delete a product
     */
    public function testUserCanDeleteAProduct()
    {
        $this->assertDatabaseHas('product_translations', [
            'product_id' => $this->product->id,
            'title' => $this->product->title,
            'body'  => $this->product->body
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'deleted_at' => null,
        ]);

        $response   = $this->delete('/product/' . $this->product->id, ['_token' => csrf_token()]);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('product_translations', [
            'title' => $this->product->title,
            'body'  => $this->product->body
        ]);

        $this->assertDatabaseMissing('products', [
            'id' => $this->product->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Store new product
     */
    public function testUserCanCreateNewProduct()
    {
        Storage::fake('public');
        $this->overrideData([
            'category_id'   => null,
            'brand_id'      => null,
            'shop_id'       => null,
            'min_quantity'  => null,
            'measure'       => null,
        ]);
        $this->assertDatabaseMissing('product_translations', $this->data);

        $this->addCsrfToken();

        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('product', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $productT  = ProductTranslation::where('title', $this->data['title'])->first();
        $product   = Product::withoutGlobalScopes()->find($productT->product_id);
        $this->removeCsrfToken();
        $this->overrideData([
            'image'          => null,
        ]);
        $this->assertDatabaseHas('product_translations', $this->data);
        Storage::disk('public')->assertExists(
            $product->getMedia()[0]->id . '/' . $product->getMedia()[0]->file_name
        );

        $response->assertSee('Redirecting');
    }

    /**
     * Update the product
     */
    public function testUserCanUpdateProduct()
    {
        Storage::fake('public');
        $this->assertDatabaseHas('product_translations', [
            'title' => $this->product->title,
            'body'  => $this->product->body
        ]);

        $this->overrideData([
            'title' => 'title_updated',
            'body'  => 'body_updated',
            'image' => UploadedFile::fake()->image('test1.jpg')
        ]);
        $this->addCsrfToken();
        $response = $this->put('/product/' . $this->product->id , $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();

        $this->overrideData([
            'image'         => null,
            'category_id'   => null,
            'brand_id'      => null,
            'shop_id'       => null,
            'min_quantity'  => null,
            'measure'       => null,
        ]);
        $this->assertDatabaseHas('product_translations', $this->data);
        Storage::disk('public')->assertExists(
            $this->product->getMedia()[0]->id . '/' . $this->product->getMedia()[0]->file_name
        );

        $response->assertSee('Redirecting');
    }
}
