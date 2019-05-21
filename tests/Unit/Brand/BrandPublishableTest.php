<?php

namespace Tests\Unit;

use App\Models\Brand;
use App\Models\User;
use Tests\BrandTestCase;
use Illuminate\Http\Response;
use App\Models\BrandTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BrandPublishableTest extends BrandTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Store new brand
     */
    public function testBrandNotPublishedByDefault()
    {
        $this->addCsrfToken();
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->getJsonRequest()->post('brand', $this->data);

        $content = json_decode($response->getContent(), true);
        $newBrand = Brand::withoutGlobalScopes()->latest()->first();;

        $this->assertEquals(false, $newBrand->published);
    }

    public function testBrandCanBePublished()
    {
        $response   = $this->get('/brand/publish/' . $this->brand->id);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals(true, $this->brand->fresh()->published);

        $response   = $this->get('/brand/unpublish/' . $this->brand->id);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals(false, $this->brand->fresh()->published);
    }

    public function testBrandCanBePublishedJSON()
    {
        $response   = $this->getJsonRequest()->get('/brand/publish/' . $this->brand->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(true, $this->brand->fresh()->published);

        $response   = $this->getJsonRequest()->get('/brand/unpublish/' . $this->brand->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(false, $this->brand->fresh()->published);
    }

    public function testUsersUnauthenticatedCannotSeeUnpublishedBrands()
    {
        $data = [
            'title' => 'published',
        ];
        $this->assertDatabaseMissing('brands', $data);

        Brand::create($data);

        $this->assertDatabaseHas('brands', $data);

        //Reload brands to clear global scope
        Brand::clearBootedModels();

        $response = $this->get('/brand');
        $response->assertDontSee($data['title']);
    }

    public function testUsersNotAdminCannotSeeUnpublishedBrands()
    {
        $data = [
            'title' => 'published',
        ];
        $this->assertDatabaseMissing('brands', $data);

        Brand::create($data);

        $this->assertDatabaseHas('brands', $data);

        //Reload brands to clear global scope
        Brand::clearBootedModels();

        $user = User::where('email', User::WHOLESALER_EMAIL)->first();
        $response = $this->actingAs($user)->get('/brand');
        $response->assertDontSee($data['title']);
    }

    public function testUsersAdminCanSeeUnpublishedBrands()
    {
        $this->assertDatabaseMissing('brands', $this->data);

        $this->addCsrfToken();
        $this->overrideData([
            'title' => 'published',
        ]);
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('brand', $this->data);

        $this->removeCsrfToken();
        unset($this->data['image']);
        $this->assertDatabaseHas('brands', $this->data);

        //Reload brands to clear global scope
        Brand::clearBootedModels();

        $user = User::where('email', User::ADMIN_EMAIL)->first();
        $response = $this->actingAs($user)->get('/brand?page=1');
        $response->assertSee($this->data['title']);
    }
}
