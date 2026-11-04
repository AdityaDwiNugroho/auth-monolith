<?php

namespace Modules\Products\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Products\Models\Product;
use Modules\Products\Requests\StoreProductRequest;
use Modules\Products\Requests\UpdateProductRequest;
use Modules\Products\Exceptions\InsufficientStockException;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('categories')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        if ($request->filled('category_ids')) {
            $product->categories()->sync($request->category_ids);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        if ($request->filled('category_ids')) {
            $product->categories()->sync($request->category_ids);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function reduceStock(Request $request, Product $product, ?int $quantity = null)
    {
        $qty = $quantity ?? (int) $request->input('quantity', 1);

        if ($product->stock < $qty) {
            throw new InsufficientStockException(
                $product->name,
                $product->stock,
                $qty
            );
        }

        $product->decrement('stock', $qty);

        return redirect()
            ->route('products.index')
            ->with('success', "Stok {$product->name} berhasil dikurangi sebanyak {$qty}.");
    }
}
