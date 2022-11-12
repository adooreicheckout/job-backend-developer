<?php

namespace App\Console\Commands\Import;

use App\Api\FakeStoreApi\FakeStoreApi;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class ImportProducts extends Command
{
    protected $signature = 'import:products
    {--id= : import single product by id}
    {--limit= : limits of quantity to import}';

    protected $description = 'Import products from fakestore api';

    public function __construct(
        private readonly FakeStoreApi $fakeStoreApi,
        private readonly ProductRepository $productRepository,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($id = $this->option('id')) {
            $this->importById($id);

        } else {
            $limit = $this->option('limit');
            $this->importBulk($limit);
        }

        return 0;
    }

    private function importById(int $id): void
    {
        $product = $this->fakeStoreApi->getProduct($id);
        $result = $this->storeProduct($id, $product);
        $this->showDashboard(collect([$result]));
    }

    private function importBulk(?int $limit = null): void
    {
        if ($limit && $limit < 1) {
            $this->error('The "limit" argument must be greater than or equal to 1');
            return;
        }

        $products = $this->fakeStoreApi->getProducts($limit);

        $results = $products->map(function (array $product) {
            return $this->storeProduct($product['id'], $product);
        });

        $this->showDashboard($results);
    }

    private function storeProduct(int $id, ?array $product): array
    {
        if (!$product) {
            return [
                'id' => $id,
                'internal_id' => null,
                'name' => null,
                'status' => 'Failed',
                'reason' => "Product not found",
            ];
        }

        $product['name'] = $product['title'];

        try {
            $validator = $this->productRepository->validate($product, $product['name'], 'name');

            if ($validator->fails()) {
                $validatorMessages = implode(', ', Arr::flatten($validator->errors()->messages()));
                throw new \Exception($validatorMessages);
            }

            $productModel = $this->productRepository
                ->updateOrCreate(['name' => $product['name']], $product);

            return [
                'id' => $product['id'],
                'internal_id' => $productModel->id,
                'name' => $product['name'],
                'status' => $this->getStatusOfProduct($productModel),
                'reason' => '',
            ];
        } catch (Throwable $e) {

            return [
                'id' => $product['id'],
                'internal_id' => null,
                'name' => $product['name'],
                'status' => 'Failed',
                'reason' => $e->getMessage(),
            ];
        }
    }

    private function showDashboard(Collection $results): void
    {
        $firstRow = $results->first();
        $headers = array_keys($firstRow);
        $headers = array_map(fn (string $header) => Str::headline($header), $headers);

        $this->table($headers, $results->toArray());
    }

    private function getStatusOfProduct(Product $productModel): string
    {
        if ($productModel->wasRecentlyCreated) {
            return 'Created';
        }

        if ($productModel->wasChanged()) {
            return 'Updated';
        }

        return 'Skipped';
    }
}
