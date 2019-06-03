<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\ShopTestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchTest extends ShopTestCase
{
    use DatabaseTransactions;

    protected $structure;

    public function setUp() : void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function testCanFetchSearchCriteria()
    {
        $response = $this->getJsonRequest()->get('/product/search/criteria');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'name', 'categories', 'brands', 'price'
        ]);
    }

    public function testCanFilterProducts()
    {
        $product = Product::first();

        $this->data = [
            'search' => [
                'price' => [
                    'min' => $product->prices[0]->price - 10,
                    'max' => $product->prices[0]->price + 10,
                ],
                'name' => $product->title,
                'categories' => [
                    [
                        'id' => $product->category->id,
                        'title' =>$product->category->title
                    ]
                ],
                'brands' => [
                    [
                        'id' => $product->brand->id,
                        'title' =>$product->brand->title
                    ],
                ]
            ]
        ];
        $this->addCsrfToken();

        $response = $this->getJsonRequest()->post('/product/search', $this->data);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals(json_decode($response->getContent(), true)['data'][0]['id'], $product->id);
    }
}
