@extends ('seeker.layouts.main', ['container' => 'container'])
@section ('content')
	@include('seeker.common.page-header', ['pageTitle' => 'Tax Information'])
	<section class="content">
		<div class="container">
		    <div class="row">
				<div class="col-12">
		            <div class="card card-info">
		            	<div class="card-header text-uppercase">Tax Information</div>
		            	@include('seeker.org._form_tax_information')
					</div>
		        </div>
		    </div>
		</div>
	</section>

    <script type="text/javascript">
    	$(document).ready(function() {
			$( '.js-fiscal-type' ).on('change', function(e) {
				if( $(this).is(":checked") ){ // check if the radio is checked
				    var val = $(this).val(); // retrieve the value
				    $('.js-agent-section-type').hide(1000);
				    $('#'+val+'_section').show(1000);
				}
			});

			// //Date picker
		 //    $('#year_established_date').datetimepicker({
		 //        format: 'L'
		 //    });

		});
    </script>

@endsection




