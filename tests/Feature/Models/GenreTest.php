<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        factory(Genre::class, 1)->create();
        $genres = Genre::all();
        $this->assertCount(1, $genres);
        $genreKey = array_keys($genres->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $genreKey
        );
    }

    public function testCreate()
    {
        $genre = Genre::create(
            [
                'name' => 'test1'
            ]
        );
        $genre->refresh();

        $this->assertEquals('test1', $genre->name);
        $this->assertTrue($genre->is_active);

        $genre = Genre::create(
            [
                'name' => 'test1',
                'is_active' => false
            ]
        );
        $this->assertFalse($genre->is_active);

        $genre = Genre::create(
            [
                'name' => 'test1',
                'is_active' => true
            ]
        );
        $this->assertTrue($genre->is_active);

        $this->assertRegExp('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $genre->id);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create(
            [
                'is_active' => false
            ]
        )->first();

        $data = [
            'name' => 'teste_name_updated',
            'is_active' => true,
        ];

        $genre->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete()
    {
        $genre = factory(Genre::class, 10)->create()->first();
        $genre->delete();

        $genres = Genre::all();
        $this->assertCount(9, $genres);
    }
}
