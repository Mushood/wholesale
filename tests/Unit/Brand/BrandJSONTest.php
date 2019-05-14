<?php

namespace Tests\Unit;

use App\Models\Brand;
use Tests\BrandTestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BrandJSONTest extends BrandTestCase
{
    use DatabaseTransactions;

    protected $structure;

    public function setUp() : void
    {
        parent::setUp();

        $this->structure = [
            'data' => [
                'title', 'views'
            ]
        ];
    }

    /**
     * Retrieve all brands
     *
     * @return void
     */
    public function testUserCanRetrieveAllBrandsJSON()
    {
        $response = $this->getJsonRequest()->get('/brand');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(Controller::PAGINATION, 'data');
    }

    /**
     * Api to store new brand
     */
    public function testUserCanCreateNewBrandJSON()
    {
        Storage::fake('public');
        $this->assertDatabaseMissing('brands', $this->data);

        $this->addCsrfToken();

        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->getJsonRequest()->post('brand', $this->data);

        $response->assertStatus(Response::HTTP_CREATED);

        $brand   = Brand::withoutGlobalScopes()->where('title', $this->data['title'])->first();
        $this->removeCsrfToken();
        $this->overrideData([
            'image'          => null,
        ]);
        $this->assertDatabaseHas('brands', $this->data);
        Storage::disk('public')->assertExists(
            $brand->getMedia()[0]->id . '/' . $brand->getMedia()[0]->file_name
        );
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to retrieve the brand
     */
    public function testUserCanRetrieveABrandJSON()
    {
        $response   = $this->getJsonRequest()->get('/brand/' . $this->brand->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to update the brand
     */
    public function testUserCanUpdateBrandJSON()
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
        $response = $this->getJsonRequest()->put('/brand/' . $this->brand->id , $this->data);

        $response->assertStatus(Response::HTTP_OK);

        $this->removeCsrfToken();
        $this->overrideData([
            'image'          => null,
        ]);
        $this->assertDatabaseHas('brands', $this->data);
        Storage::disk('public')->assertExists(
            $this->brand->getMedia()[0]->id . '/' . $this->brand->getMedia()[0]->file_name
        );
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to delete a brand
     */
    public function testUserCanDeleteABrandJSON()
    {
        $title = "unique_title";
        $this->brand->title = $title;
        $this->brand->save();

        $this->assertDatabaseHas('brands', [
            'title' => $this->brand->fresh()->title,
        ]);

        $response   = $this->getJsonRequest()->delete('/brand/' . $this->brand->id, ['_token' => csrf_token()]);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('brands', [
            'title' => $title,
        ]);
    }
}
