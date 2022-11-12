<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    public function index(): JsonResponse
    {
        $filterAndSortProperties = ['id', 'name', 'category'];

        try {
            $products = $this
                ->productRepository
                ->list()
                ->allowedFilters($filterAndSortProperties)
                ->allowedSorts($filterAndSortProperties)
                ->paginate()
                ->appends(request()->query());

            return response()->json($products);
        } catch (Throwable $e) {
            return response()->json([
                'message' => "Failed to list/filter the Products",
                'reason' => $e->getMessage(),
            ], Response::HTTP_BAD_GATEWAY);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            return response()->json($this->productRepository->findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return response()->json(['message' => "Failed to get the Product $id"], Response::HTTP_BAD_GATEWAY);
        }
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productRepository->create($request->validated());
        return response()->json($product);
    }

    public function update(ProductRequest $request, int $id): JsonResponse
    {
        try {
            $product = $this->productRepository->update($id, $request->validated());
            return response()->json($product);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return response()->json(['message' => "Failed to update the Product $id"], Response::HTTP_BAD_GATEWAY);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->productRepository->delete($id);
            return response()->json(['message' => "Product $id was successfully deleted"]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);

        } catch (Throwable $e) {
            return response()->json(['message' => "Failed to delete the Product $id"], Response::HTTP_BAD_GATEWAY);
        }
    }
}
