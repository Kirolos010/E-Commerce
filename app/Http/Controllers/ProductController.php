<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all products
        $products = Product::with('categories')->get();
        $current_price= $this->current_price();
        return view('Product.index', compact('products','current_price'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('Product.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //dd($request->image);

    // Handle the file upload
        $imagePath = $request->file('img')->store('images', 'public');

    // Create a new product
    $product=Product::create([
        'name' => $request->name,
        'img' => $imagePath, 
        'description' => $request->description,
    ]);
    $product->categories()->attach($request->categories);
    
         return redirect()->route('products.index')->with('success', 'Product added successfully.');
        // return response('ok');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        //get prodect for this id
        $product= Product::findOrFail($id);
        $categories = Category::all();
        return view('Product.edit',compact('product','categories'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $product=Product::findOrFail($id);
        $imgpath = $request->file('img')->store('images', 'public');
        $product->update([
            'name' => $request->name,
            'img' => $imgpath,  // Use 'img' instead of 'image'
            'description' => $request->description,
        ]);
        $product->categories()->sync($request->categories);
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        //delete this product;
        Product::destroy($id);
        //Reset count 
        $this->resetids();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
    //RESET COUNT ID 
protected function resetids()
{
    $products = Product::orderBy('id')->get();

    foreach ($products as $index => $product) {
        $product->id = $index + 1; 
        $product->save();
    }
    DB::statement('ALTER TABLE products AUTO_INCREMENT = ' . (count($products) + 1));
}
public function current_price(){
    $currentdate= now();
    $productsprice = DB::table('products')
        ->join('prices', 'products.id', '=', 'prices.product_id')
        ->where('prices.start_date', '<=', $currentdate)
        ->where('prices.end_date', '>=', $currentdate)
        ->select('products.id','prices.price')
        ->get();
    
    return $productsprice;
}
}
