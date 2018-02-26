@extends('layouts.master')
@section('title', 'Add Client')
@section('content')
<div id="page-content">
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Add client</h3>
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

                        <form action="{{ url('billing/client/save') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="contact_first_name">Client Name</label>
                                <input type="text" class="form-control" name="client_name" required>
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