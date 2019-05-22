<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShoppingTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    public function setUp() : void
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        $this->user = User::find(1);

        Session::start();
    }

    public function testAuthenticatedUserCanCreateCart()
    {
        //get user with no cart
        $userIds = Cart::where('user_id', '!=', null)->pluck('user_id');
        $user = User::whereNotIn('id', $userIds)->first();

        $response = $this->actingAs($user)->get('/cart');

        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('My Cart');
    }

    public function testAuthenticatedUserCanRetrieveCart()
    {
        $response = $this->actingAs($this->user)->get('/cart');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('My Cart');
    }

    public function testUnauthenticatedUserCanCreateCartWithIdentifierInSession()
    {
        $response = $this->get('/cart');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('My Cart');

        $latestCart = Cart::latest()->first();
        $response->assertSessionHas(Cart::CART_IDENTIFIER_KEY, $latestCart->identifier);
    }

    public function testAuthenticatedUserCanRetrieveCartJSON()
    {
        $response = $this->withHeaders(['Accept' => 'Application/json'])
            ->actingAs($this->user)
            ->get('/cart');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['data' => [ 'total', 'items' ]]);
    }

    public function testUnauthenticatedUserCanCreateAndRetrieveItWhenLoggingIn()
    {
        $responseFirst = $this->get('/cart');
        $latestCart = Cart::latest()->first();
        $responseFirst->assertSessionHas(Cart::CART_IDENTIFIER_KEY, $latestCart->identifier);

        //get user with no cart
        $userIds = Cart::where('user_id', '!=', null)->pluck('user_id');
        $user = User::whereNotIn('id', $userIds)->first();

        $responseSecond = $this->withSession([Cart::CART_IDENTIFIER_KEY => $latestCart->identifier])
            ->actingAs($user)
            ->get('/cart');

        $this->assertEquals($responseSecond->original->getData()['cart']->id, $latestCart->id);
        $this->assertEquals($responseSecond->original->getData()['cart']->user_id, $latestCart->fresh()->user_id);
        $this->assertEquals($responseSecond->original->getData()['cart']->user_id, $user->id);
        $this->assertEquals(
            $responseFirst->original->getData()['cart']->id, $responseSecond->original->getData()['cart']->id
        );
        $this->assertNotEquals(
            $responseFirst->original->getData()['cart']->user_id, $responseSecond->original->getData()['cart']->user_id
        );
    }

    public function testItemCanBeAddedToCart()
    {
        $product = Product::where('published', true)->first();
        $response = $this->withHeaders(['Accept' => 'Application/json'])->get('/cart/add/' . $product->id);
        $response->assertJsonStructure(['data' => [ 'total', 'items' ]]);

        $latestCart = Cart::latest()->first();
        $this->assertEquals($latestCart->items[0]->product_id, $product->id);

        $productSecond = Product::where('published', true)->skip(1)->first();
        $response = $this->withHeaders(['Accept' => 'Application/json'])->get('/cart/add/' . $productSecond->id);
        $response->assertJsonStructure(['data' => [ 'total', 'items' ]]);

        $latestCart = Cart::latest()->first();
        $this->assertEquals($latestCart->items[1]->product_id, $productSecond->id);
    }

    public function testTwoItemCannotBeAddedToCart()
    {
        $product = Product::where('published', true)->first();
        $response = $this->withHeaders(['Accept' => 'Application/json'])->get('/cart/add/' . $product->id);
        $response->assertJsonStructure(['data' => [ 'total', 'items' ]]);

        $latestCart = Cart::latest()->first();
        $this->assertEquals($latestCart->items[0]->product_id, $product->id);

        $this->expectException(\Exception::class);
        $response = $this->withHeaders(['Accept' => 'Application/json'])->get('/cart/add/' . $product->id);

    }

    public function testCartTotal()
    {
        $response = $this->get('/cart');
        $latestCart = Cart::latest()->first();
        $this->assertEquals(0, $latestCart->getTotal());

        $product1 = Product::where('published', true)->first();
        $this->withHeaders(['Accept' => 'Application/json'])->get('/cart/add/' . $product1->id);
        $this->assertEquals($product1->price, $latestCart->getTotal());

        $product2 = Product::where('published', true)->skip(1)->first();
        $this->withHeaders(['Accept' => 'Application/json'])->get('/cart/add/' . $product2->id);
        $this->assertEquals($product1->price + $product2->price, $latestCart->getTotal());
    }
}
