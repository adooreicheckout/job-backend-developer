<?php

namespace App\Api\FakeStoreApi;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class FakeStoreApi
{
    public function getProducts(?int $limit = null): Collection
    {
        $uri = config('adoorei.fakestoreapi.endpoint') . '/products';

        $query = ($limit && $limit > 0)
            ? ['limit' => $limit]
            : [];

        $response = Http::get($uri, $query)
            ->json();
        return collect($response);
    }

    public function getProduct(int $id): ?array
    {
        $uri = config('adoorei.fakestoreapi.endpoint') . "/products/$id";

        return Http::get($uri)
            ->json();
    }
}
