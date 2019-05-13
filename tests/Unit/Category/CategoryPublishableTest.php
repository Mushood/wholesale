<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\User;
use Tests\CategoryTestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryPublishableTest extends CategoryTestCase
{
    use DatabaseTransactions;

    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * Store new category
     */
    public function testCategoryNotPublishedByDefault()
    {
        $this->addCsrfToken();
        $this->data['image'] = UploadedFile::fake()->image('test.jpg');
        $response = $this->getJsonRequest()->post('category', $this->data);

        $newCategory = Category::withoutGlobalScopes()->latest()->first();

        $this->assertEquals(false, $newCategory->published);
    }

    public function testCategoryCanBePublished()
    {
        $response   = $this->get('/category/publish/' . $this->category->id);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals(true, $this->category->fresh()->published);

        $response   = $this->get('/category/unpublish/' . $this->category->id);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertEquals(false, $this->category->fresh()->published);
    }

    public function testCategoryCanBePublishedJSON()
    {
        $response   = $this->getJsonRequest()->get('/category/publish/' . $this->category->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(true, $this->category->fresh()->published);

        $response   = $this->getJsonRequest()->get('/category/unpublish/' . $this->category->id);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(false, $this->category->fresh()->published);
    }

    public function testPublishableTraitThrowsExceptionWhenNoModelFound()
    {
        $this->withoutExceptionHandling();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No Model found');
        $response   = $this->getJsonRequest()->get('/unknown/publish/' . $this->category->id);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
