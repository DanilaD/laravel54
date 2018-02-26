@extends('layouts.master')
@section('title', 'Clients')
@section('content')
<div id="page-content">
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Clients</h3>
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

                        <div class="table-responsive">
                            <a class="btn btn-default pull-right" href="{{ url('billing/client/add') }}" role="button"><i class="fa fa-plus"></i>&nbsp;add client</a>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client name</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($clients as $val)
                                    <tr>
                                        <td>{{ $val->id }}</td>
                                        <td>{{ $val->client_name }}</td>
                                        <td><a href="/billing/client/edit/{{ $val->id }}" class="btn btn-sm btn-info">edit</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <hr>
                            <div class="pull-right">
                                {{ $clients->appends(request()->input())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection