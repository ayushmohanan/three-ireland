<?php

namespace App\Http\Controllers\Api\v1\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

final class ProductsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $cacheKey = 'products_'.md5(
                $request->input('status').'|'.
                $request->input('search').'|'.
                $request->input('sort_field', 'created_at').'|'.
                $request->input('sort_order', 'desc').'|'.
                $request->input('per_page', 10).'|'.
                $request->input('page', 1)
            );

            // Redis cache calling
            $cachedData = Cache::remember($cacheKey, 3600, function () use ($request) {
                $query = Product::query();

                if ($request->filled('status')) {
                    $query->where('status', $request->input('status'));
                }

                if ($request->filled('search')) {
                    $search = $request->input('search');
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('sku', 'LIKE', "%{$search}%");
                    });
                }

                $sortField = $request->input('sort_field', 'created_at');
                $sortOrder = $request->input('sort_order', 'desc');
                $query->orderBy($sortField, $sortOrder);

                $perPage = (int) $request->input('per_page', 10);
                $products = $query->paginate($perPage);

                return [
                    'data' => $products->items(),
                    'meta' => [
                        'current_page' => $products->currentPage(),
                        'per_page' => $products->perPage(),
                        'total' => $products->total(),
                        'last_page' => $products->lastPage(),
                    ],
                ];
            });

            return response()->json([
                'statusCode' => 200,
                'status' => 'success',
                'message' => 'Products fetched successfully',
                'data' => $cachedData['data'],
                'meta' => $cachedData['meta'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching products', ['error' => $e->getMessage()]);

            return response()->json([
                'statusCode' => 500,
                'status' => 'failed',
                'message' => 'Internal Server Error',
                'data' => [],
            ]);
        }
    }
}
