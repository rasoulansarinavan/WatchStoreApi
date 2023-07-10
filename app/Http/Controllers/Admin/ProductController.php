<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "لیست محصولات";
        return view('admin.product.list', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "ایجاد محصول";
        $brands = Brand::query()->pluck('title', 'id');
        $categories = Category::query()->pluck('title', 'id');
        return view('admin.product.create', compact('title', 'brands', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $image = Product::saveImage($request->file);
        Product::query()->create([
            'title' => $request->input(['title']),
            'title_en' => $request->input(['title_en']),
            'slug' => helper::make_slug($request->input(['title'])),
            'price' => $request->input(['price']),
            'review' => $request->input(['review']),
            'count' => $request->input(['count']),
            'image' => $image,
            'guaranty' => $request->input(['guaranty']),
            'discount' => $request->input(['discount']),
            'description' => $request->input(['description']),
            'is_special' => $request->input(['is_special']) === 'on',
            'special_expiration' => ($request->input('special_expiration') !== null ? Verta::parse($request->input('special_expiration'))->datetime() : now()),
            'category_id' => $request->input(['category_id']),
            'brand_id' => $request->input(['brand_id']),
        ]);
        return redirect()->route('products.index')->with('message', 'محصول با موفقیت اضافه شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "ویرایش محصول";
        $product = Product::query()->find($id);
        $categories = Category::query()->pluck('title', 'id');
        $brands = Brand::query()->pluck('title', 'id');
        return view('admin.product.edit', compact('title', 'product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::query()->find($id);
        $image = Product::saveImage($request->file);
        $product->update([
            'title' => $request->input(['title']),
            'title_en' => $request->input(['title_en']),
            'slug' => helper::make_slug($request->input(['title'])),
            'price' => $request->input(['price']),
            'review' => $request->input(['review']),
            'count' => $request->input(['count']),
            'image' => ($request->input('file') ? $image : $product->image),
            'guaranty' => $request->input(['guaranty']),
            'discount' => $request->input(['discount']),
            'description' => $request->input(['description']),
            'is_special' => $request->input(['is_special']) === 'on',
            'special_expiration' => ($request->input('special_expiration') !== null ? Verta::parse($request->input('special_expiration'))->datetime() : now()),
            'category_id' => $request->input(['category_id']),
            'brand_id' => $request->input(['brand_id']),
        ]);
        return redirect()->route('products.index')->with('message', 'محصول با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}