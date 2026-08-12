@extends ('seeker.layouts.main', ['container' => 'container'])
@section ('content')
	@include('seeker.common.page-header', ['pageTitle' => 'Population Served'])
	<section class="content">
		<div class="container">
		    <div class="row">
		        <div class="col-12">
		            <div class="card card-info">
		            	<div class="card-header text-uppercase">Population Served</div>
					  	<div class="card-body">
					  		<div class="mb-4">
						  		<h4>Please complete the form below</h4>
						  	</div>
						  	{!! Form::open( ['method' => "POST", 'files' => false, 'id' => 'form-population-served', 'class' => 'gs-form clearfix' ]) !!}
						  	<div class="mb-4 table-responsive">
								<table class="table table-borderless min-width-tb">
								  <thead>
								    <tr>
								      <th scope="col"></th>
								      <th scope="col">Hisp</th>
								      <th scope="col">Afro-Am.</th>
								      <th scope="col">Nat-Am.</th>
								      <th scope="col">Asian</th>
								      <th scope="col">Euro.</th>
								    </tr>
								  </thead>
								  <tbody>
								    <tr>
								      <th scope="row">Projected</th>
								      <td>
								      	{!! Form::text('fid_701', null, ['class' => 'form-control']) !!}
								      </td>
								      <td>
								      	{!! Form::text('fid_702', null, ['class' => 'form-control']) !!}
								      </td>
								      <td>
								      	{!! Form::text('fid_703', null, ['class' => 'form-control']) !!}
								      </td>
								      <td>
								      	{!! Form::text('fid_704', null, ['class' => 'form-control']) !!}
								      </td>
								      <td>
								      	{!! Form::text('fid_705', null, ['class' => 'form-control']) !!}
								      </td>
								    </tr>
								    <tr>
								      <th scope="row">Actual</th>
								      <td>
								      	{!! Form::text('fid_706', null, ['class' => 'form-control']) !!}
								      </td>
								      <td>
								      	{!! Form::text('fid_707', null, ['class' => 'form-control']) !!}
								      </td>
								      <td>
								      	{!! Form::text('fid_708', null, ['class' => 'form-control']) !!}
								      </td>
								      <td>
								      	{!! Form::text('fid_709', null, ['class' => 'form-control']) !!}
								      </td>
								      <td>
								      	{!! Form::text('fid_710', null, ['class' => 'form-control']) !!}
								      </td>
								    </tr>
								  </tbody>
								</table>
							</div>
						    <div class="card-footer">
						    	{!! Form::submit('Save', ['name' => 'save', 'id' =>'id_save_btn', 'class' => 'btn btn-accent']) !!}
								{!! Form::submit('Save/Continue', ['name' => 'save-continue', 'class' => 'btn btn-accent ml-2']) !!}
		                        <button type="submit" class="btn btn-default float-right">Cancel</button>
		                    </div>
		                    {!! Form::close() !!}
					  	</div>
					</div>
		        </div>
		    </div>
		</div>
	</section>
@endsection




