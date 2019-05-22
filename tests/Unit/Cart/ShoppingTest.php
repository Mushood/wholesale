<?php

namespace Tests\Unit;

use App\Models\Cart;
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
}
