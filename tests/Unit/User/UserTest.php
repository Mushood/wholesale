<?php

namespace Tests\Unit;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends BaseTestCase
{
    use DatabaseTransactions;

    protected $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = User::find(1);

        Session::start();
    }

    public function getData()
    {

        return [
            'email'     => 'test@wholesale.com',
            'password'  => 'Secret123!',
        ];
    }

    public function testUnverifiedUserCannotAccessHomePage()
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        $response = $this->actingAs($this->user)->get('/home');

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSee('Redirecting');
        $response->assertSee('email/verify"');
    }

    public function testVerifiedUserCanAccessHomePage()
    {
        $response = $this->actingAs($this->user)->get('/home');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Dashboard');
    }

    public function testVerifiedUserCannotAccessVerificationPage()
    {
        $response = $this->actingAs($this->user)->get('/email/verify');

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSee('Redirecting');
        $response->assertSee('home"');
    }

    public function testUnverifiedUserCanAccessVerificationPage()
    {
        $this->user->email_verified_at = null;
        $this->user->save();

        $response = $this->actingAs($this->user)->get('/email/verify');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Verify Your Email Address');
    }

    public function testUserCanAccessLoginPage()
    {
        $response = $this->get('/login');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Login');
    }

    public function testUserCanAccessRegisterPage()
    {
        $response = $this->get('/register');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Register');
    }

    public function testUserCanRegisterWithVerificationEmailSent()
    {
        Notification::fake();
        $this->assertDatabaseMissing('users', $this->data);

        $this->addCsrfToken();
        $this->data['password_confirmation'] = 'Secret123!';
        $response = $this->post('/register', $this->data);

        $user = User::where('email', 'test@wholesale.com')->first();
        Notification::assertSentTo([$user], VerifyEmail::class);
        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $this->overrideData([
            'password_confirmation' => null,
            'password' => null
        ]);
        $this->assertDatabaseHas('users', $this->data);

        $response->assertSee('Redirecting');
    }

    public function testUserCanAccessResetPasswordPage()
    {
        $response = $this->get('/password/reset');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Reset Password');
        $response->assertSee('Send Password Reset Link');
    }

    public function testUserCanResetPassword()
    {
        $token = app('auth.password.broker')->createToken($this->user);
        $response = $this->get('/password/reset/' . $token);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Reset Password');
    }

    public function testUserCanBeAssignedShop()
    {
        $user = User::where('shop_id', null)->first();
        $shop = Shop::first();
        $response = $this->get('/shop/' . $shop->id .'/user/' . $user->id);

        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals($shop->id, $user->fresh()->shop_id);
    }

    public function testUserCanBeAssignedShopJSON()
    {
        $user = User::where('shop_id', null)->first();
        $shop = Shop::first();
        $response = $this->getJsonRequest()->get('/shop/' . $shop->id .'/user/' . $user->id);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($shop->id, $user->fresh()->shop_id);
    }
}
