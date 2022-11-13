<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductControllerDestroyTest extends TestCase
{
    use RefreshDatabase;

    private Collection|Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create(['name' => 'Banana', 'category' => 'Fruit']);
    }

    public function test_destroy()
    {
        $response = $this->delete(route('api.products.destroy', $this->product->id));
        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals(['message' => "Product {$this->product->id} was successfully deleted"], $response->json());
    }

    public function test_destroy_invalid_id()
    {
        $response = $this->delete(route('api.products.destroy', 'abcd'));
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function test_destroy_model_not_found()
    {
        $response = $this->delete(route('api.products.destroy', 99999));
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertEquals(['message' => 'No query results for model [App\Models\Product] 99999'], $response->json());
    }

    public function test_destroy_unexpected_error()
    {
        $this->mock(ProductRepository::class)
            ->shouldReceive('delete')
            ->andThrow(new Exception('fake exception'));

        $response = $this->delete(route('api.products.destroy', $this->product->id));
        $response->assertStatus(Response::HTTP_BAD_GATEWAY);

        $this->assertEquals(['message' => "Failed to delete the Product {$this->product->id}"], $response->json());
    }
}
