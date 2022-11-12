<?php

namespace App\Repositories;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Spatie\QueryBuilder\QueryBuilder;

class ProductRepository
{
    public function __construct(
        protected readonly Product $model,
    )
    {
    }

    public function list(): QueryBuilder
    {
        return QueryBuilder::for($this->model)
            ->defaultSort('+id')
            ->allowedFields([
                ...$this->model->getFillable(),
                ...$this->model->getDates(),
                'id',
        ]);
    }

    public function find(int $id): ?Product
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): ?Product
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $properties): Product
    {
        return $this->model->create($properties);
    }

    public function updateOrCreate(array $columnsToCheck, array $properties): Product
    {
        return $this->model->updateOrCreate($columnsToCheck, $properties);
    }

    public function update(int $id, array $properties): Product
    {
        $product = $this->findOrFail($id);
        $product->update($properties);

        return $product;
    }

    public function delete(int $id): bool|null
    {
        $product = $this->findOrFail($id);
        return $product->deleteOrFail();
    }

    public function validate(array $data, int|string|null $ignoreId = null, ?string $ignoreColumn = 'id'): Validator
    {
        $productRequestValidator = new ProductRequest();

        return ValidatorFacade::make(
            $data,
            $productRequestValidator->rules($ignoreId, $ignoreColumn),
            $productRequestValidator->messages(),
        );
    }
}
