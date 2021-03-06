<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
$categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        if (is_null($request->file('photo'))) {
            $path = '';
        }
        else {
            $photoName = preg_replace('/\s+/', '-', $request->name) .'_'. rand() .'.'. $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('photos', $photoName, 'public');
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'photo' => $path
        ]);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if (!empty($request->file('photo'))) {
            $photoName = preg_replace('/\s+/', '-', $request->name) .'_'. rand() .'.'. $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('photos', $photoName, 'public');
        }
        elseif (!empty($product->photo)) {
            $path = $product->photo;
        }
        else {
            $path = '';
        }

        if (!empty($request->input('deletePhoto'))) {
            Storage::disk('public')->delete($product->photo);
            $path = '';
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'photo' => $path
        ]);

        return redirect()->route('products.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        Storage::disk('public')->delete($product->photo);
        $product->delete();

        return redirect()->route('products.index');
    }
}
