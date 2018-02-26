@extends('layouts.master')
@section('title', 'Add Payment for doctor')
@push('reportcss')
<link href="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.css') }}" rel="stylesheet">
@endpush
@section('content')
<div id="page-content">
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Add payment for doctor</h3>
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

                            <form action="{{ url('billing/doctors/save') }}" method="post">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Doctor *</label>
                                            <select name="doctor_id" class="form-control" required>
                                                <option value="">Doctor</option>
                                                @foreach($doctors as $val)
                                                    <option value="{{ $val->id }}">{{ $val->first  }} {{ $val->last  }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Amount *</label>
                                            <input type="number" step=".01" class="form-control" name="amount" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <label class="control-label">Assessments *</label>
                                        <input type="number" step="1" class="form-control" name="assessments" required>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label">Payment Date</label>
                                            <input type="text" id="date" class="form-control" name="payment_date">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('reports')
<script src="{{ asset('plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
    $(document).ready(function(){
        var date_input=$('input[id="date"]');
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        var options={
            format: 'yyyy-mm-dd',
            container: container,
            autoclose: true,
            todayHighlight: true,
            autoclose: true,
        };
        date_input.datepicker(options);
    })
</script>
@endpush