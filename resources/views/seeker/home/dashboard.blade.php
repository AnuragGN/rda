@extends ('seeker.layouts.main', ['container' => 'container'])
@section ('content')
	@include('seeker.common.page-header', ['pageTitle' => 'Dashboard'])

	<section class="content">
		<div class="container">
		    <div class="row">
				<div class="col-12">
		            <div class="card card-info">
					  	<div class="card-body">
					  		<div class="mb-4">
						  		<h3 class="page-subtitle mt-2">Welcome Tony Welter</h3>
						  	</div>
							<div class="mb-4">

							<div class="card card-info">
								<div class="card-header text-uppercase">Organization Profile</div>
								<div class="card-body">
									<a href="{{route('gs-org-edit-profile')}}">Update your Organization Profile</a>
								</div>
							</div>

							<div class="card card-info">
								<div class="card-header text-uppercase">Current Projects / Funding Needs</div>
								<div class="card-body">
									<table class="table mb-4">
									  <thead class="thead-light">
									    <tr>
									      <th scope="col">PROGRAM NAME</th>
									      <th scope="col">CAMPAIGN DATES</th>
									    </tr>
									  </thead>
									  <tbody>
									  	<tr>
									      <td>
									      	<a href="#">
									      	Sustaining support:Testing project
									      	</a>
									      </td>
									      <td>
											2021-07 through 2021-07
									      </td>
									    </tr>
									  	<tr>
									      <td></td>
									      <td>
											<a href="">
												Add/Edit Programs
											</a>
									      </td>
									    </tr>
									  </tbody>
									</table>

									<p>
									No Programs Found
									</p>
									<a class="btn btn-primary">
										<i class="nav-icon far fa-edit"></i>
										Start a new program
									</a>



								</div>
							</div>								

								

								

									

								

								

								<h5 class="page-subtitle mt-2">Legend</h5>

								<p>print view</p>
								<p>progress report pending</p>
								<p>convert to program</p>
								

							</div>
					  	</div>
					</div>
		        </div>
		    </div>
		</div>
	</section>

@endsection





