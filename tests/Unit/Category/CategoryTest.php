<?php

namespace Tests\Unit;

use App\Models\Category;
use Tests\CategoryTestCase;
use Illuminate\Http\Response;
use App\Models\CategoryTranslation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryTest extends CategoryTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Retrieve all categories
     *
     * @return void
     */
    public function testUserCanRetrieveAllCategories()
    {
        $response = $this->get('/category');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Category Page');
    }

    /**
     * Access Create Page

    public function testUserCanAccessCreatePage()
    {
        $response = $this->get('/category/create');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Create Category');
    }
     */
    /**
     * Store new category
     */
    public function testUserCanCreateNewCategory()
    {
        Storage::fake('public');
        unset($this->data['type']);
        $this->assertDatabaseMissing('category_translations', $this->data);

        $this->addCsrfToken();
        $this->data['type'] = 'category';
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->post('category', $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        $categoryT  = CategoryTranslation::where('title', $this->data['title'])->first();
        $category   = Category::withoutGlobalScopes()->find($categoryT->category_id);
        unset($this->data['image']);
        unset($this->data['type']);
        $this->assertDatabaseHas('category_translations', $this->data);
        Storage::disk('public')->assertExists(
            $category->getMedia()[0]->id . '/' . $category->getMedia()[0]->file_name
        );

        $response->assertSee('Redirecting');
    }

    /**
     * Retrieve a category
     */
    public function testUserCanRetrieveACategory()
    {
        $this->withoutExceptionHandling();
        $response   = $this->get('/category/' . $this->category->slug);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($this->category->title);
    }

    /**
     * Access Edit Page
     */
    public function testUserCanAccessEditPage()
    {
        $response = $this->get("/category/{$this->category->id}/edit");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Create Category');
    }

    /**
     * Update the category
     */
    public function testUserCanUpdateCategory()
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
        $response = $this->put('/category/' . $this->category->id , $this->data);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->removeCsrfToken();
        unset($this->data['image']);
        unset($this->data['type']);
        $this->assertDatabaseHas('category_translations', $this->data);
        Storage::disk('public')->assertExists(
            $this->category->getMedia()[0]->id . '/' . $this->category->getMedia()[0]->file_name
        );

        $response->assertSee('Redirecting');
    }

    /**
     * Delete a category
     */
    public function testUserCanDeleteACategory()
    {
        $this->assertDatabaseHas('category_translations', [
            'category_id' => $this->category->id,
            'title' => $this->category->title,
            'description'  => $this->category->description
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $this->category->id,
            'deleted_at' => null,
        ]);

        $response   = $this->delete('/category/' . $this->category->id, ['_token' => csrf_token()]);

        $response->assertStatus(Response::HTTP_FOUND);

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
