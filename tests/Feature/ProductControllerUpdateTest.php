<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductControllerUpdateTest extends TestCase
{
    use RefreshDatabase;

    private Collection|Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create(['name' => 'Banana', 'category' => 'Fruit', 'image_url' => 'https://banana.com']);
    }

    public function test_update()
    {
        $payload = [
            'name' => 'Keyboard',
            'category' => 'Eletronic',
            'price' => 15.99,
            'description' => 'Keyboard ABTN 2 Black / Grey wirelles',
            'image_url' => 'https://keyboard.com',
        ];

        $response = $this->put(route('api.products.update', $this->product->id), $payload);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(function (AssertableJson $json) use ($payload) {
            $json->where('id', $this->product->id)
                ->where('name', $payload['name'])
                ->where('category', $payload['category'])
                ->where('price', $payload['price'])
                ->where('description', $payload['description'])
                ->where('image_url', $payload['image_url'])
                ->has('created_at')
                ->has('updated_at')
                ->etc();
        });
    }

    public function test_update_invalid_id()
    {
        $payload = [
            'name' => 'Keyboard',
            'category' => 'Eletronic',
            'price' => 15.99,
            'description' => 'Keyboard ABTN 2 Black / Grey wirelles',
            'image_url' => 'https://keyboard.com',
        ];
        $response = $this->put(route('api.products.update', 'abcd'), $payload);
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function test_update_model_not_found()
    {
        $payload = [
            'name' => 'Keyboard',
            'category' => 'Eletronic',
            'price' => 15.99,
            'description' => 'Keyboard ABTN 2 Black / Grey wirelles',
            'image_url' => 'https://keyboard.com',
        ];
        $response = $this->put(route('api.products.update', 99999), $payload);

        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertEquals(['message' => 'No query results for model [App\Models\Product] 99999'], $response->json());
    }

    public function test_update_unexpected_error()
    {
        $this->mock(ProductRepository::class)
            ->shouldReceive('update')
            ->andThrow(new Exception('fake exception'));

        $payload = [
            'name' => 'Keyboard',
            'category' => 'Eletronic',
            'price' => 15.99,
            'description' => 'Keyboard ABTN 2 Black / Grey wirelles',
            'image_url' => 'https://keyboard.com',
        ];

        $response = $this->put(route('api.products.update', $this->product->id), $payload);
        $response->assertStatus(Response::HTTP_BAD_GATEWAY);

        $this->assertEquals(['message' => "Failed to update the Product {$this->product->id}"], $response->json());
    }

    public function test_update_only_required_properties()
    {
        $payload = [
            'name' => 'Keyboard',
            'category' => 'Eletronic',
            'price' => 15.99,
            'description' => 'Keyboard ABTN 2 Black / Grey wirelles',
        ];

        $response = $this->put(route('api.products.update', $this->product->id), $payload);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(function (AssertableJson $json) use ($payload) {
            $json->where('id', $this->product->id)
                ->where('name', $payload['name'])
                ->where('category', $payload['category'])
                ->where('price', $payload['price'])
                ->where('description', $payload['description'])
                ->where('image_url', $this->product->image_url)
                ->has('created_at')
                ->has('updated_at')
                ->etc();
        });
    }

    /**
     * @test
     * @dataProvider invalidProductsDataProvider
     */
    public function test_store_with_invalid_payload($payload)
    {
        $response = $this->put(route('api.products.update', $this->product->id), $payload);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseHas('products', [
            'name' => 'Banana',
            'category' => 'Fruit',
            'image_url' => 'https://banana.com'
        ]);
    }

    // data provider
    public function invalidProductsDataProvider(): array
    {
        $payload = [
            'name' => 'Keyboard',
            'category' => 'Eletronic',
            'price' => 15.99,
            'description' => 'Keyboard ABTN 2 Black / Grey wirelles',
        ];

        $payloadOptions = $this->makeCombanationOfPartialPayload($payload);
        $withoutRequiredProperty = array_map(function (array $payload) {
            return [$payload];
        }, $payloadOptions);

        return [
            ...$withoutRequiredProperty,
            [[...$payload, 'name' => Str::random(256)]],
            [[...$payload, 'price' => 2.509]],
            [[...$payload, 'price' => 0]],
            [[...$payload, 'price' => null]],
            [[...$payload, 'price' => 'zero']],
            [[...$payload, 'description' => Str::random(3001)]],
            [[...$payload, 'category' => Str::random(256)]],
            [[...$payload, 'image_url' => Str::random(256)]],
        ];
    }

    // helper function to generate combanation of failure results
    private function makeCombanationOfPartialPayload($payload): array
    {
        $results = [[]];

        foreach ($payload as $key => $element) {
            foreach ($results as $combination) {
                $results[] = array_merge([$key => $element], $combination);
            }
        }

        return array_filter($results, function (array $result) use ($payload) {
            return count($result) !== count($payload);
        });
    }
}
