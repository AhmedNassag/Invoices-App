<?php

namespace App\Http\Controllers;

use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections')->with('sections',$sections);
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
                'section_name' => 'required|unique:sections|max:255',
                'description'  => 'required',
            ],
            [
                'section_name.required' => 'يرجى إدخال اسم القسم',
                'section_name.unique'   => 'اسم القسم موجود بالفعل',
                'description.required' => 'يرجى إدخال الملاحظات',
            ]
        );

        Section::create
        ([
            'section_name'=>$request->section_name,
            'description' =>$request->description,
            'created_by'  =>(Auth::user()->name),
        ]);
        session()->flash('add','تم إضافة القسم بنجاح');
        return redirect('/section');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $section_id = $request->section_id;
        $this->validate($request,
            [
                'section_name' => 'required|max:255'.$section_id,
                'description'  => 'required',
            ],
            [
                'section_name.required' => 'يرجى إدخال اسم القسم',
                'description.required' => 'يرجى إدخال الملاحظات',
            ]
        );
        $section = Section::findOrFail($section_id);
        $section->update
        ([
            'section_name' => $request->section_name,
            'description'  => $request->description,
        ]);
        session()->flash('edit','تم تعديل القسم بنجاح');
        return redirect('/section');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $section = Section::findOrFail($request->section_id);
        $section->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/section');
    }
}
