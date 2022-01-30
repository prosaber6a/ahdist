<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::orderBy('updated_at', 'desc')->get();
        return view('product.index', ['products' => $product]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:60',
            'unit' => 'integer|max:50',
            'size' => 'nullable|string|max:60',
            'status' => 'required|integer|max:1'
        ]);

        Product::create([
            'name' => $request->name,
            'unit' => $request->unit,
            'size' => $request->size,
            'status' => $request->status
        ]);

        return redirect()->route('products')->with('success', 'Successfully product added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        if (intval(Auth::user()->user_type) !== 1) {
            return redirect()->route('dashboard')->withError('Sorry permission required');
        }
        return view('product.edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if (intval(Auth::user()->user_type) !== 1) {
            return redirect()->route('dashboard')->withError('Sorry permission required');
        }

        $request->validate([
            'name' => 'required|string|max:60',
            'unit' => 'integer|max:50',
            'size' => 'nullable|string|max:60',
            'status' => 'required|integer|max:1'
        ]);

        $product->name = $request->name;
        $product->unit = $request->unit;
        $product->size = $request->size;
        $product->status = $request->status;

        try {
            $product->save();
        } catch (\Exception $exception) {
            return redirect()->route('edit_product', $product->id)->withError($exception->getMessage())->withInput();
        }

        return redirect()->route('products')->with('success', 'Successfully product updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (intval(Auth::user()->user_type) !== 1) {
            return redirect()->route('dashboard')->withError('Sorry permission required');
        }
        // Delete Other Table Info
        try {
            $product->delete();
        } catch (\Exception $exception) {
            return redirect()->route('products')->withError($exception->getMessage());
        }

        return redirect()->route('products')->with('success', 'Successfully product deleted');
    }
}
