<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\ProductTestCase;
use Illuminate\Http\Response;
use App\Models\ProductTranslation;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductJSONTest extends ProductTestCase
{
    use DatabaseTransactions;

    protected $structure;

    public function setUp() : void
    {
        parent::setUp();

        $this->structure = [
            'data' => [
                'title', 'subtitle', 'introduction' , 'body' , 'views' , 'category', 'shop', 'brand', 'min_quantity',
                'measure', 'prices'
            ]
        ];
    }

    /**
     * Retrieve all products
     *
     * @return void
     */
    public function testUserCanRetrieveAllProductsJSON()
    {
        $response = $this->getJsonRequest()->get('/product');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(Controller::PAGINATION, 'data');
    }

    /**
     * Api to retrieve the product
     */
    public function testUserCanRetrieveAProductJSON()
    {
        $response   = $this->getJsonRequest()->get('/product/' . $this->product->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to delete a product
     */
    public function testUserCanDeleteAProductJSON()
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

        $response   = $this->getJsonRequest()->delete('/product/' . $this->product->id, ['_token' => csrf_token()]);
        $response->assertStatus(Response::HTTP_OK);

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
     * Api to store new product
     */
    public function testUserCanCreateNewProductJSON()
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
        $response = $this->getJsonRequest()->post('product', $this->data);

        $response->assertStatus(Response::HTTP_CREATED);

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
        $response->assertJsonStructure($this->structure);
    }



    /**
     * Api to update the product
     */
    public function testUserCanUpdateProductJSON()
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
        $response = $this->getJsonRequest()->put('/product/' . $this->product->id , $this->data);

        $response->assertStatus(Response::HTTP_OK);

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
        $response->assertJsonStructure($this->structure);
    }
}
