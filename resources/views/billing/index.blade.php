@extends('layouts.master')
@section('title', 'Billing')
@push('css-admin')
@endpush
@section('content')
				<!--Page Title-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<div id="page-title">
					<h1 class="page-header text-overflow">Billing Dashboard</h1>
				</div>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End page title-->


				<!--Breadcrumb-->
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<ol class="breadcrumb">
					<li><a href="{{ url('home') }}">{{ config('app.name') }}</a></li>
					<li class="active">Billing Dashboard</li>
				</ol>
				<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
				<!--End breadcrumb--> 
                
                <div id="page-content">
					<div class="row">
						<div class="col-md-12">
							<div class="pull-right">
								<div class="table-toolbar-right">
									<a class="btn btn-primary" href="#" data-toggle="modal" data-target="#add_modal">Add Client</a>
									<div class="modal fade" id="add_modal" aria-hidden="true" style="display: none;">
										<div class="modal-dialog">
											<div class="modal-content">
												<form action="https://24-hr-vmd-v2/billing/client/add" method="post">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
														<h4 class="modal-title"><i class="fa fa-arrow-circle-right"></i> Add new Client</h4>
													</div>
													<div class="modal-body">
														<div class="form-group">
															<label for="contact_first_name">Client Name</label>
															<input type="text" class="form-control" name="client_name" required>
														</div>
														<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close
														</button>
														<button type="submit" class="btn btn-primary">Save</button>
													</div>
												</form>
											</div><!-- /.modal-content -->
										</div><!-- /.modal-dialog -->
									</div><!-- /.modal -->
								</div>
							</div>
						</div>
					</div>
                	<div class="row">
                    	<div class="col-md-12">
                        	<div class="panel panel-primary">


								<!--Panel heading-->
								<div class="panel-heading">
									<div class="panel-control">
					
										<!--Nav tabs-->
										<ul class="nav nav-tabs">
											<li class="active"><a data-toggle="tab" href="#Clients">Clients</a></li>
                                        	<li><a data-toggle="tab" href="#ReceivedPayments">Received payments</a></li>
											<li><a data-toggle="tab" href="#AddPayments">Add payments</a></li>
											<!--
											<li	><a data-toggle="tab" href="#AddPayments">Add billing</a></li>
											<li><a data-toggle="tab" href="#sysconfig">Main</a></li>
											<li><a data-toggle="tab" href="#demo-tabs-box-1">Client Payments</a></li>
                                            <li><a data-toggle="tab" href="#addMember">Doctor Payments</a></li>
                                            -->
										</ul>
					
									</div>
								</div>
								<!--Panel body-->
								<div class="panel-body">
									<?php if($success == 1 OR isset($_GET['success'])) { ?>
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>Success</strong> We have successfully processed your request.
                                    </div>
                                    <? } ?>

                                    
                                    @if (Session::get('errors'))
											<div class="alert alert-danger media fade in"><button type="button" class="close" data-dismiss="alert">×</button><strong>ERROR!</strong> We have failed processed your request</div>
											<div class="clearfix"></div>
										@endif

										@if (Session::get('success'))
											<div class="alert alert-success media fade in"><button type="button" class="close" data-dismiss="alert">×</button><strong>Success</strong> We have successfully processed your request</div>
											<div class="clearfix"></div>
											@endif
                                    
									<!--Tabs content-->
									<div class="tab-content">

										<div id="Clients" class="tab-pane fade in active">
											<table class="table table-striped">
												<thead>
												<tr>
													<th>#</th>
													<th>Client name</th>
													<th></th>
												</tr>
												</thead>

												<tbody>
												<?php
												foreach($clients as $val) {
													echo '<tr>';
													echo '<td>'.$val->id.'</td>';
													echo '<td>'.$val->client_name.'</td>';
													echo '<td><a href="/billing/client/edit/'.$val->id.'" class="btn btn-sm btn-info">edit</a></td>';
													echo '</tr>';
												}
												?>
												</tbody>
											</table>
										</div>

										<div id="ReceivedPayments" class="tab-pane fade">

										</div>

										<div id="AddPayments" class="tab-pane fade">

											<form action="{{ url('billing/payments/add') }}" method="post">
												{{ csrf_field() }}
												<input type="hidden" class="form-control" name="id" value="">
												<div class="form-group">
													<label class="control-label">Client # </label>
													<input type="text" class="form-control" name="client_name" value="" required>
												</div>
													<button type="submit" class="btn btn-default">Update</button>
											</form>

										</div>

                                    	<div id="sysconfig" class="tab-pane fade">

                                        </div>
                                        
										<div id="demo-tabs-box-1" class="tab-pane fade">

										</div>

                                        <div id="addMember" class="tab-pane fade">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
@endsection

@push('script-admin')
<script>


function validateCheckboxes(){
    var checkedBoxes = $('label.active');
    var count = checkedBoxes.length;
    if(count == 0){
        alert('No categories chosen!');
        return false;
    }
    return true;
}
</script>
@endpush