<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // Ambil parameter category_slug jika ada
        $categorySlug = $request->query('category_slug');

        // Buat query produk dengan relasi productImages
        $query = Product::with('productImages');

        // Tambahkan kondisi jika category_slug ada
        if ($categorySlug) {
            $query->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        }

        // Dapatkan hasil paginasi
        $products = $query->paginate(10); // Ubah angka 10 sesuai kebutuhan

        return response()->json([
            'message' => 'Get all data product',
            'data' => $products
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Eager load the category and productImages relationships
        $product->load([
            'productImages',
            'category' => function ($query) {
                $query->select('id', 'name'); // Always include 'id' to maintain the relationship
            }
        ]);

        return response()->json([
            'message' => 'List Products',
            'data' => $product
        ]);
    }
}
