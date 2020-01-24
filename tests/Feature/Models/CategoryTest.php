<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Category::class, 1)->create();
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $categoryKey = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $categoryKey
        );
    }

    public function testCreate()
    {
        $category = Category::create(
            [
                'name' => 'test1'
            ]
        );
        $category->refresh();

        $this->assertEquals('test1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category = Category::create(
            [
                'name' => 'test1',
                'description' => null
            ]
        );
        $this->assertNull($category->description);

        $category = Category::create(
            [
                'name' => 'test1',
                'description' => 'test_description'
            ]
        );
        $this->assertEquals('test_description', $category->description);

        $category = Category::create(
            [
                'name' => 'test1',
                'is_active' => false
            ]
        );
        $this->assertFalse($category->is_active);

        $category = Category::create(
            [
                'name' => 'test1',
                'is_active' => true
            ]
        );
        $this->assertTrue($category->is_active);

        $this->assertRegExp('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $category->id);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create(
            [
                'description' => 'test_description',
                'is_active' => false
            ]
        )->first();

        $data = [
            'name' => 'teste_name_updated',
            'description' => 'test_description_updated',
            'is_active' => true,
        ];

        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        $category = factory(Category::class, 10)->create()->first();
        $category->delete();

        $categories = Category::all();
        $this->assertCount(9, $categories);
    }
}
