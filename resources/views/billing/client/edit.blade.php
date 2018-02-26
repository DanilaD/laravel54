@extends('layouts.master')
@section('title', 'Edit Client')
@section('content')
<div id="page-content">
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Edit client # {{ $client->id }}</h3>
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
                            <input type="hidden" class="form-control" name="id" value="{{$client->id}}">
                            <div class="form-group">
                                <label class="control-label">Client Name</label>
                                <input type="text" class="form-control" name="client_name" value="{{$client->client_name}}" required>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="submit" class="btn btn-default">Update</button>
                                <a class="btn btn-danger" href="{{ url('billing/client/delete/'.$client->id) }}" onclick="return confirm_alert(this);">Delete</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script-admin')
<script type="text/javascript">
    function confirm_alert(node) {
        return confirm("You are about to permanently delete this client. Please click on OK to continue.");
    }
</script>
@endpush