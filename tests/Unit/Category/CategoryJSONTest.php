<?php

namespace Tests\Unit;

use App\Models\Category;
use Tests\CategoryTestCase;
use Illuminate\Http\Response;
use App\Models\CategoryTranslation;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryJSONTest extends CategoryTestCase
{
    use DatabaseTransactions;

    protected $structure;

    public function setUp() : void
    {
        parent::setUp();

        $this->structure = [
            'data' => [
                'title', 'description', 'type' , 'level' , 'views' 
            ]
        ];
    }

    /**
     * Retrieve all categories
     *
     * @return void
     */
    public function testUserCanRetrieveAllCategoriesJSON()
    {
        $response = $this->getJsonRequest()->get('/category');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(Controller::PAGINATION, 'data');
    }

    /**
     * Api to store new category
     */
    public function testUserCanCreateNewCategoryJSON()
    {
        Storage::fake('public');
        unset($this->data['type']);
        $this->assertDatabaseMissing('category_translations', $this->data);

        $this->addCsrfToken();

        $this->data['type'] = 'category';
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->getJsonRequest()->post('category', $this->data);

        $response->assertStatus(Response::HTTP_CREATED);

        $categoryT  = CategoryTranslation::where('title', $this->data['title'])->first();
        $category   = Category::withoutGlobalScopes()->find($categoryT->category_id);
        unset($this->data['_token']);
        unset($this->data['image']);
        unset($this->data['type']);
        $this->assertDatabaseHas('category_translations', $this->data);
        Storage::disk('public')->assertExists(
            $category->getMedia()[0]->id . '/' . $category->getMedia()[0]->file_name
        );
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to retrieve the category

    public function testUserCanRetrieveACategoryJSON()
    {
        $response   = $this->getJsonRequest()->get('/category/' . $this->category->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure($this->structure);
    }
     */

    /**
     * Api to update the category
     */
    public function testUserCanUpdateCategoryJSON()
    {
        Storage::fake('public');
        $this->assertDatabaseHas('category_translations', [
            'title' => $this->category->title,
            'description'  => $this->category->description
        ]);

        $this->overrideData([
            'title' => 'title_updated',
            'description'  => 'description_updated',
            'image' => UploadedFile::fake()->image('test1.jpg')
        ]);
        $this->addCsrfToken();
        $response = $this->getJsonRequest()->put('/category/' . $this->category->id , $this->data);

        $response->assertStatus(Response::HTTP_OK);

        unset($this->data['_token']);
        unset($this->data['image']);
        unset($this->data['type']);
        $this->assertDatabaseHas('category_translations', $this->data);
        Storage::disk('public')->assertExists(
            $this->category->getMedia()[0]->id . '/' . $this->category->getMedia()[0]->file_name
        );
        $response->assertJsonStructure($this->structure);
    }

    /**
     * Api to delete a category
     */
    public function testUserCanDeleteACategoryJSON()
    {
        $this->withoutExceptionHandling();
        $this->assertDatabaseHas('category_translations', [
            'category_id' => $this->category->id,
            'title' => $this->category->title,
            'description'  => $this->category->description
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $this->category->id,
            'deleted_at' => null,
        ]);

        $response   = $this->getJsonRequest()->delete('/category/' . $this->category->id, ['_token' => csrf_token()]);
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('category_translations', [
            'category_id' => $this->category->id,
            'title' => $this->category->title,
            'description'  => $this->category->description
        ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $this->category->id,
            'deleted_at' => null,
        ]);
    }
}
