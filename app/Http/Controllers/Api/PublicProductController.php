<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicProductController extends Controller
{
    /**
     * Get list of active products with filtering and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::where('is_active', true);

        // Filter by type (web_app, android, ios, custom)
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by featured
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Search in title, short description, or tech stack
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Sort order
        $sortBy = $request->input('sort', 'latest');
        match ($sortBy) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'featured'   => $query->orderBy('is_featured', 'desc')->latest(),
            default      => $query->latest(),
        };

        $perPage = $request->integer('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ],
            'filters' => [
                'categories' => Product::where('is_active', true)->whereNotNull('category')->distinct()->pluck('category'),
                'types'      => ['web_app', 'android', 'ios', 'custom'],
                'tech_stacks'=> Product::where('is_active', true)->pluck('tech_stack')->flatten()->filter()->unique()->values(),
            ]
        ]);
    }

    /**
     * Get single product details by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Related products in the same category or type
        $related = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category', $product->category)
                  ->orWhere('type', $product->type);
            })
            ->limit(4)
            ->get();

        return response()->json([
            'success' => true,
            'product' => array_merge($product->toArray(), [
                'thumbnail_url' => $product->thumbnail_url,
                'gallery_urls'  => $product->gallery_urls,
            ]),
            'related' => $related->map(fn($r) => array_merge($r->toArray(), [
                'thumbnail_url' => $r->thumbnail_url,
            ])),
        ]);
    }

    /**
     * Get featured products for homepage showcase.
     */
    public function featured(): JsonResponse
    {
        $products = Product::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(6)
            ->get();

        // If not enough featured, fallback to latest
        if ($products->count() < 4) {
            $products = Product::where('is_active', true)
                ->latest()
                ->limit(6)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $products->map(fn($p) => array_merge($p->toArray(), [
                'thumbnail_url' => $p->thumbnail_url,
            ])),
        ]);
    }
}
