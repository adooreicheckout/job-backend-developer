<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductControllerShowTest extends TestCase
{
    use RefreshDatabase;

    private Collection|Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create(['name' => 'Banana', 'category' => 'Fruit']);
    }

    public function test_show()
    {
        $response = $this->get(route('api.products.show', $this->product->id));
        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals($this->product->toArray(), $response->json());
    }

    public function test_show_invalid_id()
    {
        $response = $this->get(route('api.products.show', 'abcd'));
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function test_show_model_not_found()
    {
        $response = $this->get(route('api.products.show', 99999));
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertEquals(['message' => 'No query results for model [App\Models\Product] 99999'], $response->json());
    }

    public function test_show_unexpected_error()
    {
        $this->mock(ProductRepository::class)
            ->shouldReceive('findOrFail')
            ->andThrow(new Exception('fake exception'));

        $response = $this->get(route('api.products.show', $this->product->id));
        $response->assertStatus(Response::HTTP_BAD_GATEWAY);

        $this->assertEquals(['message' => "Failed to get the Product {$this->product->id}"], $response->json());
    }
}
