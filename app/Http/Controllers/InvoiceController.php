<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Invoice_attachments;
use App\Invoice_details;
use App\Section;
use App\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Notifications\AddInvoice;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\MyEventClass;


class invoiceController extends Controller
{

    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }


    public function create()
    {
        $sections = Section::all();
        return view('invoices.invoice_create', compact('sections'));
    }


    public function store(Request $request)
    {
        Invoice::create
        ([
            'invoice_number'    => $request->invoice_number,
            'invoice_date'      => $request->invoice_Date,
            'due_date'          => $request->Due_date,
            'product'           => $request->product,
            'section_id'        => $request->Section,
            'amount_collection' => $request->Amount_collection,
            'amount_commission' => $request->Amount_Commission,
            'discount'          => $request->Discount,
            'value_vat'         => $request->Value_VAT,
            'rate_vat'          => $request->Rate_VAT,
            'total'             => $request->Total,
            'status'            => 'غير مدفوعة',
            'value_status'      => 2,
            'note'              => $request->note,
        ]);

        $invoice_id = Invoice::latest()->first()->id;
        Invoice_details::create
        ([
            'invoice_id'     => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product'        => $request->product,
            'section'        => $request->Section,
            'status'         => 'غير مدفوعة',
            'value_status'   => 2,
            'note'           => $request->note,
            'user'           => (Auth::user()->name),
        ]);

        if($request->hasFile('pic'))
        {
            $invoice_id                  = Invoice::latest()->first()->id;
            $image                       = $request->file('pic');
            $file_name                   = $image->getClientOriginalName();
            $invoice_number              = $request->invoice_number;
            $attachments                 = new Invoice_attachments();
            $attachments->file_name      = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->created_by     = Auth::user()->name;
            $attachments->invoice_id     = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        // add invoice notification
        //         $user = User::first();
        //         Notification::send($user, new AddInvoice($invoice_id));

        $user = User::get();
        $Invoice = Invoice::latest()->first();
        Notification::send($user, new \App\Notifications\Add_invoice_new($Invoice));
        //event(new MyEventClass('hello world'));
        session()->flash('add', 'تم اضافة الفاتورة بنجاح');
        return redirect('/invoice');
    }


    public function show($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        return view('invoices.status_update', compact('invoices'));
    }


    public function edit($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        $sections = Section::all();
        return view('invoices.invoice_edit', compact('sections', 'invoices'));
    }


    public function update(Request $request)
    {
        $invoices = Invoice::findOrFail($request->invoice_id);
        $invoices->update
        ([
            'invoice_number'    => $request->invoice_number,
            'invoice_date'      => $request->invoice_Date,
            'due_date'          => $request->Due_date,
            'product'           => $request->product,
            'section_id'        => $request->Section,
            'amount_collection' => $request->Amount_collection,
            'amount_commission' => $request->Amount_Commission,
            'discount'          => $request->Discount,
            'value_vat'         => $request->Value_VAT,
            'rate_vat'          => $request->Rate_VAT,
            'total'             => $request->Total,
            'note'              => $request->note,
        ]);
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return redirect('/invoice');
    }


    public function destroy(Request $request)
    {
        $id       = $request->invoice_id;
        $invoices = Invoice::where('id', $id)->first();
        $Details  = Invoice_attachments::where('invoice_id', $id)->first();
        $id_page  = $request->id_page;

        if (!$id_page==2)
        {
            if (!empty($Details->invoice_number))
            {
                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
            }
            $invoices->forceDelete();
            session()->flash('delete');
            return redirect('/invoice');
        }
        else
        {
            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/Archive');
        }
    }


    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("product_name", "id");
        return json_encode($products);
    }


    public function status_update($id, Request $request)
    {
        $invoices = Invoice::findOrFail($id);
        if ($request->Status === 'مدفوعة')
        {
            $invoices->update
            ([
                'value_status' => 1,
                'status'       => $request->Status,
                'payment_date' => $request->Payment_Date,
            ]);
            Invoice_details::create
            ([
                'invoice_id'     => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product'        => $request->product,
                'section'        => $request->Section,
                'status'         => $request->Status,
                'value_status'   => 1,
                'note'           => $request->note,
                'payment_date'   => $request->Payment_Date,
                'user'           => (Auth::user()->name),
            ]);
        }
        else
        {
            $invoices->update
            ([
                'value_status' => 3,
                'status'       => $request->Status,
                'payment_date' => $request->Payment_Date,
            ]);
            Invoice_details::create
            ([
                'invoice_id'     => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product'        => $request->product,
                'section'        => $request->Section,
                'status'         => $request->Status,
                'value_status'   => 3,
                'note'           => $request->note,
                'payment_date'   => $request->Payment_Date,
                'user'           => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoice');
    }


    public function Invoice_Paid()
    {
        $invoices = Invoice::where('value_status', 1)->get();
        return view('invoices.invoices_paid',compact('invoices'));
    }


    public function Invoice_UnPaid()
    {
        $invoices = Invoice::where('value_status',2)->get();
        return view('invoices.invoices_unpaid',compact('invoices'));
    }


    public function Invoice_Partial()
    {
        $invoices = Invoice::where('value_status',3)->get();
        return view('invoices.invoices_partial',compact('invoices'));
    }


    public function Print_invoice($id)
    {
        $invoices = Invoice::where('id', $id)->first();
        return view('invoices.Print_invoice',compact('invoices'));
    }


    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }


    public function MarkAsRead_all (Request $request)
    {
        $userUnreadNotification= auth()->user()->unreadNotifications;
        if($userUnreadNotification)
        {
            $userUnreadNotification->markAsRead();
            return back();
        }
    }


    public function unreadNotifications_count()
    {
        //return auth()->user()->unreadNotifications->count();
    }


    public function unreadNotifications()
    {
        /*foreach (auth()->user()->unreadNotifications as $notification)
        {
            return $notification->data['title'];
        }*/
    }
}

/*
namespace App\Http\Controllers;

use App\Invoice;
use App\Invoice_attachments;
use App\Invoice_details;
use App\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices')->with('invoices',$invoices);
    }


    public function create()
    {
        $sections = Section::all();
        return view('invoices.invoice_create')->with('sections',$sections);
    }


    public function store(Request $request)
    {
        Invoice::create
        ([
            'invoice_number'   =>$request->invoice_number,
            'invoice-date'     =>$request->invoice_date,
            'due_date'         =>$request->due_date,
            'product'          =>$request->product,
            'section_id'       =>$request->section,
            'amount_collection'=>$request->amount_collection,
            'amount_commission'=>$request->amount_commission,
            'discount'         =>$request->discount,
            'value_vat'        =>$request->value_vat,
            'rate_vat'         =>$request->rate_vat,
            'total'            =>$request->total,
            'status'           =>'غير مدفوعة',
            'value_status'     =>2,
            'note'             =>$request->note,
            //'payment_date'     =>$request->payment_date,
        ]);

        $invoice_id = Invoice::latest()->first()->id;
        Invoice_details::create
        ([
            'invoice_id'    =>$invoice_id,
            'invoice_number'=>$request->invoice_number,
            'product'       =>$request->product,
            'section'       =>$request->section,
            'status'        =>'غير مدفوعة',
            'value_status'  =>2,
            'note'          =>$request->note,
            'user'          =>(Auth::user()->name),
        ]);

        if($request->hasFile('pic'))
        {
            //$this->validate($request,['pic'=>'required|mimes:pdf|max:10000'],['pic.mimes'=>'خطأ..تم حفظ الفاتورة ولم يتم حفظ المرفق لابد أن يكون بصيغة pdf']);
            $invoice_id                  = Invoice::latest()->first()->id;
            $image                       = $request->file('pic');
            $file_name                   = $image->getClientOriginalName();
            $invoice_number              = $request->invoice_number;
            $attachments                 = new Invoice_attachments();
            $attachments->file_name      = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->created_by     = Auth::user()->name;
            $attachments->invoice_id     = $invoice_id;
            $attachments->save();
            //move pic
            $image_name = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/'.$invoice_number),$image_name);
        }
        session()->flash('add','تم إضافة الفاتورة بنجاح');
        return back();
    }


    public function show(Invoice $invoice)
    {
        //
    }


    public function edit(Invoice $invoice)
    {
        $invoices = Invoice::where('id',$id)->first();
        $sections = Section::all();
        return view('invoices.invoice_edit')
            ->with('invoices',$invoices)
            ->with('sections',$sections);
    }


    public function update(Request $request)
    {
        $invoice = Invoice::findOrFail($request->invoice_id);
        $invoice->update
        ([
            'invoice-number'   =>$request->invoice-number,
            'invoice-date'     =>$request->invoice-date,
            'due-date'         =>$request->due-date,
            'product'          =>$request->product,
            'section_id'       =>$request->section_id,
            'amount_collection'=>$request->amount_collection,
            'amount_commission'=>$request->amount_commission,
            'discount'         =>$request->discount,
            'value_vat'        =>$request->value_vat,
            'rate_vat'         =>$request->rate_vat,
            'total'            =>$request->total,
            'note'             =>$request->note,
        ]);
        session()->flash('edit','تم تعديل الفاتورة بنجاح');
        return back();
    }


    public function destroy(Request $request)
    {
        $id          = $request->invoice_id;
        $invoices    = Invoice::where('id',$id);
        $attachments = Invoice_attachments::where('invoice_id',$id)->first();
        if(!empty($attachments->invoice_number))
        {
            Storage::disk('public_uploads')->delete($attachments->invoice_number.'/'.$attachments->file_name);
        }
        $invoices->Delete();
        session()->flash('delete','تم حذف الفاتورة بنجاح');
        return redirect('/invoice');
    }

    public function getProducts($id)
    {
        $products = DB::table('products')->where('section_id',$id)->pluck('product_name','id');
        return json_encode($products);
    }
}
