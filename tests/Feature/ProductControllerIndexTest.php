<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductControllerIndexTest extends TestCase
{
    use RefreshDatabase;

    protected array $products = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->products[] = Product::factory()->create(['name' => 'Banana', 'category' => 'Fruit']);
        $this->products[] = Product::factory()->create(['name' => 'Mouse', 'category' => 'Eletronic']);
        $this->products[] = Product::factory()->create(['name' => 'Apple', 'category' => 'Fruit']);
        $this->products[] = Product::factory()->create(['name' => 'Keyboard', 'category' => 'Eletronic']);
        $this->products[] = Product::factory()->create(['name' => 'Beanbag', 'category' => 'Furniture']);
        $this->products[] = Product::factory()->create(['name' => 'Fan', 'category' => 'Eletronic']);
    }

    public function test_index()
    {
        $response = $this->get(route('api.products.index'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('from', 1)
                ->where('total', 6)
                ->where('per_page', 15)
                ->where('data.0.name', $this->products[0]->name)
                ->where('data.0.category', $this->products[0]->category)
                ->where('data.1.name', $this->products[1]->name)
                ->where('data.1.category', $this->products[1]->category)
                ->where('data.2.name', $this->products[2]->name)
                ->where('data.2.category', $this->products[2]->category)
                ->where('data.3.name', $this->products[3]->name)
                ->where('data.3.category', $this->products[3]->category)
                ->where('data.4.name', $this->products[4]->name)
                ->where('data.4.category', $this->products[4]->category)
                ->where('data.5.name', $this->products[5]->name)
                ->where('data.5.category', $this->products[5]->category)
                ->missing('data.6')
                ->etc();
        });
    }

    /*
     * expected
     * name     | category
     * --------------------
     * Banana   | Fruit
     * Beanbag  | Furniture
     * Fan      | Eletronic
     */
    public function test_index_with_filter_by_name()
    {
        $uri = route('api.products.index', [
            'filter' => ['name' => 'an'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('from', 1)
                ->where('total', 3)
                ->where('per_page', 15)
                ->where('data.0.name', $this->products[0]->name)
                ->where('data.0.category', $this->products[0]->category)
                ->where('data.1.name', $this->products[4]->name)
                ->where('data.1.category', $this->products[4]->category)
                ->where('data.2.name', $this->products[5]->name)
                ->where('data.2.category', $this->products[5]->category)
                ->missing('data.3')
                ->etc();
        });
    }

    /*
     * expected
     * name     | category
     * --------------------
     * Banana   | Fruit
     * Mouse    | Eletronic
     * Apple    | Fruit
     */
    public function test_index_with_filter_by_category()
    {
        $uri = route('api.products.index', [
            'filter' => ['category' => 'it'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('from', 1)
                ->where('total', 3)
                ->where('per_page', 15)
                ->where('data.0.name', $this->products[0]->name)
                ->where('data.0.category', $this->products[0]->category)
                ->where('data.1.name', $this->products[2]->name)
                ->where('data.1.category', $this->products[2]->category)
                ->where('data.2.name', $this->products[4]->name)
                ->where('data.2.category', $this->products[4]->category)
                ->missing('data.3')
                ->etc();
        });
    }

    /*
     * expected
     * name     | category
     * --------------------
     * Keyboard | Eletronic
     * Fan      | Eletronic
     */
    public function test_index_with_filter_by_name_and_category()
    {
        $uri = route('api.products.index', [
            'filter' => [
                'name' => 'a',
                'category' => 'tronic',
            ],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('from', 1)
                ->where('total', 2)
                ->where('per_page', 15)
                ->where('data.0.name', $this->products[3]->name)
                ->where('data.0.category', $this->products[3]->category)
                ->where('data.1.name', $this->products[5]->name)
                ->where('data.1.category', $this->products[5]->category)
                ->missing('data.2')
                ->etc();
        });
    }

    public function test_index_with_field_by_not_allowed_property()
    {
        $uri = route('api.products.index', [
            'filter' => [
                'description' => 'a',
            ],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_BAD_GATEWAY);

        $response->assertJson(function (AssertableJson $json) {
            $json->missing('data')
                ->where('message', 'Failed to list/filter the Products')
                ->where('reason', 'Requested filter(s) `description` are not allowed. Allowed filter(s) are `id, name, category`.')
                ->etc();
        });
    }

    /*
     * expected
     * name     | category
     * --------------------
     * Apple    | Eletronic
     * Banana   | Eletronic
     * Beanbag  | Eletronic
     * Keyboard | Eletronic
     * Mouse    | Eletronic
     */
    public function test_index_sort_by_name_asc()
    {
        $uri = route('api.products.index', [
            'sort' => ['name'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('from', 1)
                ->where('total', 6)
                ->where('per_page', 15)
                ->where('data.0.name', $this->products[2]->name)
                ->where('data.0.category', $this->products[2]->category)
                ->where('data.1.name', $this->products[0]->name)
                ->where('data.1.category', $this->products[0]->category)
                ->where('data.2.name', $this->products[4]->name)
                ->where('data.2.category', $this->products[4]->category)
                ->where('data.3.name', $this->products[5]->name)
                ->where('data.3.category', $this->products[5]->category)
                ->where('data.4.name', $this->products[3]->name)
                ->where('data.4.category', $this->products[3]->category)
                ->where('data.5.name', $this->products[1]->name)
                ->where('data.5.category', $this->products[1]->category)
                ->missing('data.6')
                ->etc();
        });
    }

    /*
     * expected
     * name     | category
     * --------------------
     * Beanbag  | Furniture
     * Banana   | Fruit
     * Apple    | Fruit
     * Mouse    | Eletronic
     * Keyboard | Eletronic
     * Fan      | Eletronic
     */
    public function test_index_sort_by_category_desc()
    {
        $uri = route('api.products.index', [
            'sort' => ['-category'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('from', 1)
                ->where('total', 6)
                ->where('per_page', 15)
                ->where('data.0.name', $this->products[4]->name)
                ->where('data.0.category', $this->products[4]->category)
                ->where('data.1.name', $this->products[0]->name)
                ->where('data.1.category', $this->products[0]->category)
                ->where('data.2.name', $this->products[2]->name)
                ->where('data.2.category', $this->products[2]->category)
                ->where('data.3.name', $this->products[1]->name)
                ->where('data.3.category', $this->products[1]->category)
                ->where('data.4.name', $this->products[3]->name)
                ->where('data.4.category', $this->products[3]->category)
                ->where('data.5.name', $this->products[5]->name)
                ->where('data.5.category', $this->products[5]->category)
                ->missing('data.6')
                ->etc();
        });
    }

    /*
     * expected
     * name     | category
     * --------------------
     * Beanbag  | Furniture
     * Apple    | Fruit
     * Banana   | Fruit
     * Fan      | Eletronic
     * Keyboard | Eletronic
     * Mouse    | Eletronic
     */
    public function test_index_sort_by_category_desc_and_name_asc()
    {
        $uri = route('api.products.index', [
            'sort' => ['-category', 'name'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('from', 1)
                ->where('total', 6)
                ->where('per_page', 15)
                ->where('data.0.name', $this->products[4]->name)
                ->where('data.0.category', $this->products[4]->category)
                ->where('data.1.name', $this->products[2]->name)
                ->where('data.1.category', $this->products[2]->category)
                ->where('data.2.name', $this->products[0]->name)
                ->where('data.2.category', $this->products[0]->category)
                ->where('data.3.name', $this->products[5]->name)
                ->where('data.3.category', $this->products[5]->category)
                ->where('data.4.name', $this->products[3]->name)
                ->where('data.4.category', $this->products[3]->category)
                ->where('data.5.name', $this->products[1]->name)
                ->where('data.5.category', $this->products[1]->category)
                ->missing('data.6')
                ->etc();
        });
    }

    public function test_index_sort_by_not_allowed_property()
    {
        $uri = route('api.products.index', [
            'sort' => ['description'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_BAD_GATEWAY);

        $response->assertJson(function (AssertableJson $json) {
            $json->missing('data')
                ->where('message', 'Failed to list/filter the Products')
                ->where('reason', 'Requested sort(s) `description` is not allowed. Allowed sort(s) are `id, name, category`.')
                ->etc();
        });
    }

    /*
     * expected
     * name     | category
     * --------------------
     * Beanbag  | Furniture
     * Banana   | Fruit
     * Keyboard | Eletronic
     */
    public function test_index_filter_by_name_and_sort_by_category_desc()
    {
        $uri = route('api.products.index', [
            'filter' => ['name' => 'b'],
            'sort' => ['-category'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('from', 1)
                ->where('total', 3)
                ->where('per_page', 15)
                ->where('data.0.name', $this->products[4]->name)
                ->where('data.0.category', $this->products[4]->category)
                ->where('data.1.name', $this->products[0]->name)
                ->where('data.1.category', $this->products[0]->category)
                ->where('data.2.name', $this->products[3]->name)
                ->where('data.2.category', $this->products[3]->category)
                ->missing('data.3')
                ->etc();
        });
    }

    public function test_index_selected_only_name_field()
    {
        $uri = route('api.products.index', [
            'fields' => ['products' => 'name'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('from', 1)
                ->where('total', 6)
                ->where('per_page', 15)
                ->where('data.0', ['name' => $this->products[0]->name])
                ->where('data.1', ['name' => $this->products[1]->name])
                ->where('data.2', ['name' => $this->products[2]->name])
                ->where('data.3', ['name' => $this->products[3]->name])
                ->where('data.4', ['name' => $this->products[4]->name])
                ->where('data.5', ['name' => $this->products[5]->name])
                ->missing('data.6')
                ->etc();
        });
    }

    public function test_index_selected_id_name_and_updated_at_field()
    {
        $uri = route('api.products.index', [
            'fields' => ['products' => 'id,name,updated_at'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->missing('data.0.category')->etc();
        });
        $response->assertJsonStructure([
            'current_page',
            'from',
            'total',
            'per_page',
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function test_index_filter_by_name_sort_by_category_desc_select_only_category()
    {
        $uri = route('api.products.index', [
            'filter' => ['name' => 'b'],
            'sort' => ['-category'],
            'fields' => ['products' => 'category'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('data.0.category', $this->products[4]->category)
                ->where('data.1.category', $this->products[0]->category)
                ->where('data.2.category', $this->products[3]->category)
                ->missing('data.0.name')
                ->missing('data.1.name')
                ->missing('data.2.name')
                ->missing('data.3')
                ->etc();
        });

        $response->assertJsonStructure(['data' => ['*' => ['category']]]);
    }

    public function test_index_selected_not_allowed_property()
    {
        $uri = route('api.products.index', [
            'fields' => ['products' => 'price_with_fee'],
        ]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_BAD_GATEWAY);

        $response->assertJson(function (AssertableJson $json) {
            $json->missing('data')
                ->where('message', 'Failed to list/filter the Products')
                ->where('reason', 'Requested field(s) `products.price_with_fee` are not allowed. Allowed field(s) are `products.name, products.price, products.description, products.category, products.image_url, products.created_at, products.updated_at, products.id`.')
                ->etc();
        });
    }

    public function test_index_paginate_page_1()
    {
        Product::factory()->count(25)->create();

        $uri = route('api.products.index', ['page' => 1]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 1)
                ->where('from', 1)
                ->where('last_page', 3)
                ->where('to', 15)
                ->where('total', 31)
                ->has('data.0.name')
                ->etc();
        });
    }

    public function test_index_paginate_page_2()
    {
        Product::factory()->count(25)->create();

        $uri = route('api.products.index', ['page' => 2]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 2)
                ->where('from', 16)
                ->where('last_page', 3)
                ->where('to', 30)
                ->where('total', 31)
                ->has('data.0.name')
                ->etc();
        });
    }

    public function test_index_sort_by_category_desc_select_only_category_paginate_page_2()
    {
        Product::factory()->count(25)->create();

        $uri = route('api.products.index', [
            'sort' => ['-category'],
            'fields' => ['products' => 'category'],
            'page' => 2,
        ]);

        $response = $this->get($uri);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->missing('data.0.name')->etc();
        });

        $response->assertJsonStructure(['data' => ['*' => ['category']]]);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 2)
                ->where('from', 16)
                ->where('last_page', 3)
                ->where('to', 30)
                ->where('total', 31)
                ->has('data.0.category')
                ->etc();
        });
    }

    public function test_index_paginate_page_99_without_results()
    {
        Product::factory()->count(25)->create();

        $uri = route('api.products.index', ['page' => 99]);

        $response = $this->get($uri);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('current_page', 99)
                ->where('from', null)
                ->where('last_page', 3)
                ->where('to', null)
                ->where('total', 31)
                ->where('data', [])
                ->etc();
        });
    }
}
