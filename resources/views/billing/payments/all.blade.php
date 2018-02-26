@extends('layouts.master')
@section('title', 'Payments')
@push('reportcss')
<link href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.css') }}" rel="stylesheet">
@endpush
@section('content')
<!--Content-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-content">
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Received Payments</h3>
                </div>
                <div class="panel-body">
                    <div class="pad-btm">
                        @if (Session::get('errors'))
                            <div class="alert alert-danger media fade in"><button type="button" class="close" data-dismiss="alert">×</button><strong>ERROR!</strong> We have failed processed your request</div>
                            <div class="clearfix"></div>
                        @endif

                        @if (Session::get('success'))
                            <div class="alert alert-success media fade in"><button type="button" class="close" data-dismiss="alert">×</button><strong>Success</strong> We have successfully processed your request</div>
                            <div class="clearfix"></div>
                        @endif

                        <div class="pad-btm form-inline">
                            <div class="row">
                                <form action="{!! url('billing/payments') !!}" method="get">
                                    {{ csrf_field() }}
                                    <div class="col-sm-2 table-toolbar-left">
                                        <input class="form-control" type="number" min="1" name="id" @if (Request::get('id')) value="{{Request::get('id')}}" @endif  placeholder="Payment ID" >
                                    </div>

                                    <div class="col-sm-2 table-toolbar-left">
                                        <select class="form-control" name="clients_id" class="form-control" style="width: 100%;">
                                            <option value="">Client Name</option>
                                                @foreach($clients as $val)
                                                    <option value="{{ $val->id }}"
                                                        @if (Request::get('clients_id') == $val->id) selected @endif
                                                    >{{ $val->client_name }}</option>
                                                @endforeach
                                        </select>
                                    </div>

                                    <div class="col-sm-4 table-toolbar-left" id="dp-range">
                                        <div class="input-daterange input-group" id="datepicker">
                                            <input type="text" class="form-control" name="invoice_date_start" placeholder="Invoice Date" @if (Request::get('invoice_date_start')) value="{{Request::get('invoice_date_start')}}" @endif />
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="form-control" name="invoice_date_end" placeholder="Invoice Date" @if (Request::get('invoice_date_end')) value="{{Request::get('invoice_date_end')}}" @endif />
                                        </div>
                                        <input class="form-check-input" id="invoice_date_no" type="checkbox" value="1" @if (Request::get('invoice_date_no')) checked @endif name="invoice_date_no">
                                        <label class="form-check-label" for="invoice_date_no">
                                            Without invoice date
                                        </label>
                                    </div>

                                    <div class="col-sm-4 table-toolbar-left" id="dp-range">
                                        <div class="input-daterange input-group" id="datepicker">
                                            <input type="text" class="form-control" name="payment_date_start" placeholder="Payment Date" @if (Request::get('payment_date_start')) value="{{Request::get('payment_date_start')}}" @endif />
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="form-control" name="payment_date_end" placeholder="Payment Date" @if (Request::get('payment_date_end')) value="{{Request::get('payment_date_end')}}" @endif />
                                        </div>
                                        <input class="form-check-input" id="payment_date_no" type="checkbox" value="1" @if (Request::get('payment_date_no')) checked @endif name="payment_date_no">
                                        <label class="form-check-label" for="payment_date_no">
                                            Without payment date
                                        </label>
                                    </div>

                                    <div class="col-sm-12 table-toolbar-left">
                                        <button type="submit" name="filter" class="btn btn-primary">Filter Results</button>
                                        <a class="btn btn-default pull-right" href="{{ url('billing/payments/add') }}" role="button"><i class="fa fa-plus"></i>&nbsp;add payment</a>
                                    </div>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client name</th>
                                    <th>Assessments</th>
                                    <th>Amount</th>
                                    <th>Invoice Date</th>
                                    <th>Payment Date</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($payments as $val)
                                    <tr>
                                        <td>{{ $val->id }}</td>
                                        <td>{{ $val->client_name }}</td>
                                        <td>{{ $val->assessments }}</td>
                                        <td>{{ $val->amount }}</td>
                                        <td>{{ $val->invoice_date }}</td>
                                        <td>{{ $val->payment_date }}</td>
                                        <td><a href="/billing/payments/edit/{{ $val->id }}" class="btn btn-sm btn-info">edit</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <hr>
                            <div class="pull-left">
                                <button type="submit" name="export" class="btn btn-success">Download Result</button>
                            </div>

                            </form>
                            <div class="pull-right">
                                {{ $payments->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End content-->
@push('reports')
<script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
    $('#dp-range .input-daterange').datepicker({
        format: "yyyy-mm-dd",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
    });
</script>
@endpush