@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <!--Internal Notify -->
    <link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection

@section('title')
    الفواتير
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/قائمة الفواتير</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')

    @if(session()->has('add'))
        <script>
            window.onload = function ()
            {
                notif
                ({
                    msg : 'تم إضافة الفاتورة بنجاح',
                    type: 'success'
                })
            }
        </script>
    @endif

    @if(session()->has('edit'))
        <script>
            window.onload = function ()
            {
                notif
                ({
                    msg : 'تم تعديل الفاتورة بنجاح',
                    type: 'success'
                })
            }
        </script>
    @endif

    @if(session()->has('delete'))
        <script>
            window.onload = function ()
            {
                notif
                ({
                    msg : 'تم حذف الفاتورة بنجاح',
                    type: 'success'
                })
            }
        </script>
    @endif
        <!-- row -->
        <div class="row">
            <!-- row opened -->

            <!--div-->
            <div class="col-xl-12">
                <div class="card mg-b-20">
                    <div class="card-header pb-0">
                        <a href="invoice/create" class="modal-effect btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> &nbsp;إضافة فاتورة
                        </a>

                        <a class="modal-effect btn btn-sm btn-primary" href="{{ url('export_invoices') }}" style="color:white">
                            <i class="fas fa-file-download"></i>&nbsp;تصدير إكسيل
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="table key-buttons text-md-nowrap">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">رقم الفاتورة</th>
                                        <th class="border-bottom-0">تاريخ الفاتورة</th>
                                        <th class="border-bottom-0">تاريخ الإستحقاق</th>
                                        <th class="border-bottom-0">المنتج</th>
                                        <th class="border-bottom-0">القسم</th>
                                        <th class="border-bottom-0">الخصم</th>
                                        <th class="border-bottom-0">نسبة الضريبة</th>
                                        <th class="border-bottom-0">قيمة الضريبة</th>
                                        <th class="border-bottom-0">الإجمالى</th>
                                        <th class="border-bottom-0">الحالة</th>
                                        <th class="border-bottom-0">ملاحظات</th>
                                        <th class="border-bottom-0">العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=0; @endphp
                                    @foreach($invoices as $invoice)
                                        @php $i++; @endphp
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$invoice->invoice_number}}</td>
                                            <td>{{$invoice->invoice_date}}</td>
                                            <td>{{$invoice->due_date}}</td>
                                            <td>{{$invoice->product}}</td>
                                            <td>
                                                <a href="{{url('invoice_details')}}/{{$invoice->id}}">{{$invoice->section->section_name}}</a>
                                            </td>
                                            <td>{{$invoice->discount}}</td>
                                            <td>{{$invoice->rate_vat}}</td>
                                            <td>{{$invoice->value_vat}}</td>
                                            <td>{{$invoice->total}}</td>
                                            <td>
                                                @if($invoice->value_status == 1)
                                                    <span class="text-success">{{$invoice->status}}</span>
                                                @elseif($invoice->value_status == 2)
                                                    <span class="text-danger">{{$invoice->status}}</span>
                                                @else
                                                    <span class="text-warning">{{$invoice->status}}</span>
                                                @endif
                                            </td>
                                            <td>{{$invoice->note}}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary btn-sm" data-toggle="dropdown" type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                                    <div class="dropdown-menu tx-13">
                                                        <a class="dropdown-item" href="{{url('invoice_edit')}}/{{$invoice->id}}">
                                                            <i class="text-danger fas fa-edit"></i>&nbsp;
                                                            تعديل الفاتورة
                                                        </a>

                                                        <a class="dropdown-item" href="#" data-invoice_id="{{$invoice->id}}" data-toggle="modal" data-target="#delete_invoice">
                                                            <i class="text-danger fas fa-trash-alt"></i>&nbsp;
                                                            حذف الفاتورة
                                                        </a>

                                                        <a class="dropdown-item" href="{{URL::route('status_show',[$invoice->id])}}" data-invoice_id="{{$invoice->id}}">
                                                            <i class="text-success fas fa-money-bill"></i>&nbsp;&nbsp;
                                                            تغيير حالة الدفع
                                                        </a>

                                                        <!-- <a class="dropdown-item" href="#" data-invoice_id="{{$invoice->id}}" data-toggle="modal" data-target="#Transfer_invoice">
                                                            <i class="text-warning fas fa-exchange-alt"></i>&nbsp;
                                                            نقل إلى الأرشيف
                                                            </a> -->

                                                        <a class="dropdown-item" href="Print_invoice/{{ $invoice->id }}">
                                                            <i class="text-success fas fa-print"></i>&nbsp;
                                                            طباعة الفاتورة
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/div-->

            <!-- Delete Modal -->
            <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="examleModalLabel1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="examleModalLabel1">حذف الفاتورة</h6>
                            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <form action="{{route('invoice.destroy','test')}}" method="POST">
                            {{method_field('delete')}}
                            {{csrf_field()}}
                            <div class="modal-body">
                                <p>هل انت متأكد من عملية الحذف..؟؟</p>
                                <input type="hidden" name="invoice_id" id="invoice_id" value="">
                            </div>
                            <div class="modal-footer">
                                <button class="btn ripple btn-danger" type="submit">حذف</button>
                                <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">إلغاء</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Delete Modal-->

            <!-- ارشيف الفاتورة -->
            <div class="modal fade" id="Transfer_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">ارشفة الفاتورة</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <form action="{{ route('invoice.destroy', 'test') }}" method="post">
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                        </div>
                                <div class="modal-body">
                                    هل انت متاكد من عملية الارشفة ؟
                                    <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                    <input type="hidden" name="id_page" id="id_page" value="2">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                                    <button type="submit" class="btn btn-success">تاكيد</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- row closed -->
        </div>
        <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
    <!--Internal Datatable js -->
    <script src="{{URL::asset('assets/js/table-data.js')}}"></script>
    <!--Internal Notify js -->
    <script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>
    <script>
        $('#delete_invoice').on('show.bs.modal',function (event)
        {
            var button       = $(event.relatedTarget)
            var invoice_id   = button.data('invoice_id')
            var modal        = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
    </script>
@endsection
