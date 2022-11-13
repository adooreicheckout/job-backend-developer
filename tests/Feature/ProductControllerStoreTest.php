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

class ProductControllerStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store()
    {
        $payload = [
            'name' => 'Banana',
            'category' => 'Fruit',
            'price' => 2.50,
            'description' => 'Silk Banana',
            'image_url' => 'https://google.com',
        ];

        $response = $this->post(route('api.products.store'), $payload);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(function (AssertableJson $json) use ($payload) {
            $json->where('name', $payload['name'])
                ->where('category', $payload['category'])
                ->where('price', $payload['price'])
                ->where('description', $payload['description'])
                ->where('image_url', $payload['image_url'])
                ->has('id')
                ->has('created_at')
                ->has('updated_at')
                ->etc();
        });
    }

    public function test_store_only_required_properties()
    {
        $payload = [
            'name' => 'Banana',
            'category' => 'Fruit',
            'price' => 2.50,
            'description' => 'Silk Banana',
        ];

        $response = $this->post(route('api.products.store'), $payload);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(function (AssertableJson $json) use ($payload) {
            $json->where('name', $payload['name'])
                ->where('category', $payload['category'])
                ->where('price', $payload['price'])
                ->where('description', $payload['description'])
                ->has('id')
                ->has('created_at')
                ->has('updated_at')
                ->etc();
        });
    }

    public function test_store_with_non_existent_property()
    {
        $payload = [
            'name' => 'Banana',
            'category' => 'Fruit',
            'price' => 2.50,
            'description' => 'Silk Banana',
            'image_url' => 'https://google.com',
            'fake_property' => 'fake_value',
        ];

        $response = $this->post(route('api.products.store'), $payload);
        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson(function (AssertableJson $json) use ($payload) {
            $json->where('name', $payload['name'])
                ->where('category', $payload['category'])
                ->where('price', $payload['price'])
                ->where('description', $payload['description'])
                ->where('image_url', $payload['image_url'])
                ->has('id')
                ->has('created_at')
                ->has('updated_at')
                ->missing('fake_property')
                ->etc();
        });
    }

    /**
     * @dataProvider invalidProductsDataProvider
     */
    public function test_store_with_invalid_payload($payload)
    {
        $response = $this->post(route('api.products.store'), $payload);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseCount('products', 0);
    }

    // data provider
    public function invalidProductsDataProvider(): array
    {
        $payload = [
            'name' => 'Banana',
            'category' => 'Fruit',
            'price' => 2.50,
            'description' => 'Silk Banana',
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

    // helper function to generate combanation of partial payload
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
