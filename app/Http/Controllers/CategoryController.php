<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all();
        return view('Category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('Category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        Category::create([
            'name'=>$request->name,
        ]);
        return redirect()->route('categories.index')->with('success', 'Category added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category,$id)
    {
        //get Category
        $category= Category::findOrFail($id);
        return view('Category.edit',compact('category'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //get Category
        $category= Category::findOrFail($id);
        //update data
        $category->update([
            'name'=>$request->name,
        ]);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //get Category and remove it 
        Category::destroy($id);
        $this->resetids();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    //resort id 
    protected function resetids()
{
    $Categories = Category::orderBy('id')->get();

    foreach ($Categories as $index => $Category) {
        $Category->id = $index + 1; 
        $Category->save();
    }
    DB::statement('ALTER TABLE products AUTO_INCREMENT = ' . (count($Categories) + 1));
}
}
