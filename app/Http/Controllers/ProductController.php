<?php

namespace App\Http\Controllers;

use App\Product;
use App\Section;
use Illuminate\Http\Request;

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
        $sections = Section::all();
        return view('products.products')
            ->with('products',$products)
            ->with('sections',$sections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate
        (
            [
                'product_name' => 'required|max:255',
                'section_id'   => 'required',
                'description'  => 'required',
            ],
            [
                'product_name.required' => 'يرجى إدخال اسم المنتج',
                'section_id.required'   => 'يرجى إدخال اسم القسم',
                'description.required' => 'يرجى إدخال الملاحظات',
            ]
        );

        Product::create
        ([
            'product_name'=>$request->product_name,
            'section_id'  =>$request->section_id,
            'description' =>$request->description,
        ]);
        session()->flash('add','تم إضافة القسم بنجاح');
        return redirect('/product');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = Section::where('section_name',$request->section_name)->first()->id;
        $this->validate($request,
            [
                'product_name' => 'required|max:255'.$id,
                'description'  => 'required',
            ],
            [
                'product_name.required' => 'يرجى إدخال اسم المنتج',
                'description.required' => 'يرجى إدخال الملاحظات',
            ]
        );
        $product = Product::findOrFail($request->product_id);
        $product->update
        ([
            'product_name' => $request->product_name,
            'description'  => $request->description,
            'section_id'   => $id,
        ]);
        session()->flash('edit','تم تعديل القسم بنجاح');
        return redirect('/product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/product');
    }
}
