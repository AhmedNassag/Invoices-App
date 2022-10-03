<?php

namespace App\Http\Controllers;

use App\Invoice_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $this->validate($request,
        ['file_name'=>'mimes:pdf,jpeg,png,jpg'],
        ['file_name.mimes'=>'pdf,jpeg,png,jpg صيغة الملف يجب أن تكون']
        );
        $image                      = $request->file('file_name');
        $file_name                  = $image->getClientOriginalName();
        $attachment                 = new Invoice_attachments();
        $attachment->file_name      = $file_name;
        $attachment->invoice_number = $request->invoice_number;
        $attachment->invoice_id     = $request->invoice_id;
        $attachment->created_by     = Auth::user()->name;
        //move pic
        $image_name                 = $request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('Attachments/'.$request->invoice_number),$image_name);
        session('add','تم إضافة المرفق بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice_attachments $invoice_attachments)
    {
        //
    }
}
