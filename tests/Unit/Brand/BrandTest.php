<?php

namespace Tests\Unit;

use App\Models\Brand;
use Tests\BrandTestCase;
use Illuminate\Http\Response;
use App\Models\BrandTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BrandTest extends BrandTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Retrieve all brands
     *
     * @return void
     */
    public function testUserCanRetrieveAllBrands()
    {
        $response = $this->get('/brand');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('All Brands');
    }

    /**
     * Access Create Page
     */
    public function testUserCanAccessCreatePage()
    {
        $response = $this->get('/brand/create');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Create Brand');
    }

    /**
     * Store new brand
     */
    public function testUserCanCreateNewBrand()
    {
        Storage::fake('public');
        $this->assertDatabaseMissing('brands', $this->data);

        $this->addCsrfToken();
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('brand', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $brand   = Brand::withoutGlobalScopes()->where('title', $this->data['title'])->first();
        $this->overrideData([
            'image'    => null
        ]);
        $this->assertDatabaseHas('brands', $this->data);
        Storage::disk('public')->assertExists(
            $brand->getMedia()[0]->id . '/' . $brand->getMedia()[0]->file_name
        );

        $response->assertSee('Redirecting');
    }

    /**
     * Retrieve a brand
     */
    public function testUserCanRetrieveABrand()
    {
        $this->withoutExceptionHandling();
        $response   = $this->get('/brand/' . $this->brand->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($this->brand->title);
    }

    /**
     * Access Edit Page
     */
    public function testUserCanAccessEditPage()
    {
        $response = $this->get("/brand/{$this->brand->id}/edit");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Create Brand');
    }

    /**
     * Update the brand
     */
    public function testUserCanUpdateBrand()
    {
        Storage::fake('public');
        $this->assertDatabaseHas('brands', [
            'title' => $this->brand->title,
        ]);

        $this->overrideData([
            'title' => 'title_updated',
            'image' => UploadedFile::fake()->image('test1.jpg')
        ]);
        $this->addCsrfToken();
        $response = $this->put('/brand/' . $this->brand->id , $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $this->overrideData([
            'image'          => null,
        ]);
        $this->assertDatabaseHas('brands', $this->data);
        Storage::disk('public')->assertExists(
            $this->brand->getMedia()[0]->id . '/' . $this->brand->getMedia()[0]->file_name
        );

        $response->assertSee('Redirecting');
    }

    /**
     * Delete a brand
     */
    public function testUserCanDeleteABrand()
    {
        $title = "unique_title";
        $this->brand->title = $title;
        $this->brand->save();

        $this->assertDatabaseHas('brands', [
            'title' => $this->brand->title,
        ]);

        $response   = $this->delete('/brand/' . $this->brand->id, ['_token' => csrf_token()]);

        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseMissing('brands', [
            'title' => $title,
        ]);
    }
}
