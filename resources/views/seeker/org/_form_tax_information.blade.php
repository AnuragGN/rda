<?php
$yes_no_options = ['0' => 'No', '1' => 'Yes'];
$level_1_options = ['Foreign Charity' => 'Foreign Charity', 'Not Registered' => 'Not Registered'];
?>

{!! Form::model($org, ['method' => "POST", 'files' => false, 'id' => 'form-tax-information', 'class' => 'gs-form clearfix' ]) !!}
<div class="card-body">
	<div class="mb-5">
  		<h5 class="card-title">Please complete the following:</h5>
  	</div>
	<div class="clearfix"></div>
	    <div class="row">
		    <div class="col-8">
				<div class="form-group row">
					{!! Form::label('irs_name', 'IRS Name', ['class' => 'col-sm-4 col-form-label text-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('irs_name', null, ['class' => 'form-control']) !!}
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-10 offset-sm-1 col-form-label">
						<h5>Is your organization operating under the auspices of a fiscal sponsor?</h5>
					</label>
				</div>
				<div class="form-group">
					<div class="form-check col-sm-10 offset-sm-1">
					  {{ Form::radio('r', 'grantee' , true, ['class' => 'form-check-input js-fiscal-type']) }}
                      <label class="form-check-label"><h5>No, my organization does not have a fiscal sponsor.</h5></label>
                    </div>
				</div>
				<div id="grantee_section" class="js-agent-section-type">
					<div class="form-group row">
						{!! Form::label('year_established', 'Date Established', ['class' => 'col-sm-4 col-form-label text-right']) !!}
						<div class="col-sm-8">
						  	<div class="input-group date" id="year_established_date" data-target-input="nearest">
						  		{!! Form::text('year_established', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#year_established_date']) !!}
	                        <div class="input-group-append" data-target="#year_established_date" data-toggle="datetimepicker">
	                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
	                        </div>
	                    </div>
						</div>
					</div>
					<div class="form-group row">
						{!! Form::label('ein_number', 'EIN Number', ['class' => 'col-sm-4 col-form-label text-right']) !!}
						<div class="col-sm-8">
							{!! Form::text('ein_number', null, ['class' => 'form-control', 'required' => 'required']) !!}
						  	<div class="form-check">
			                    {!! Form::checkbox('ein_exempt', true, false, ['class' => 'form-check-input', 'id' => 'ein_exempt']) !!}
			                    {!! Form::label('ein_exempt', 'Check if church or synagogue', ['class' => 'form-check-label']) !!}
			                </div>
						</div>
					</div>
					<div class="form-group row">
						{!! Form::label('financials_audited', 'Financials Audited?', ['class' => 'col-sm-4 col-form-label text-right']) !!}
						<div class="col-sm-5">
							{!! Form::select('financials_audited', $yes_no_options, '', ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group row">
						{!! Form::label('level1', 'Charity Type', ['class' => 'col-sm-4 col-form-label text-right']) !!}
						<div class="col-sm-5">
							{!! Form::select('level1', [null=>'Select One'] +  $level_1_options, '', ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group row">
						{!! Form::label('level2', 'IRS Registered Charity', ['class' => 'col-sm-4 col-form-label text-right']) !!}
						<div class="col-sm-5">
							{!! Form::select('level2', [null=>'Select'], '', ['class' => 'form-control']) !!}
						</div>
					</div>
					<div class="form-group row mb-5">
						{!! Form::label('level3', '501(c)(3) Category', ['class' => 'col-sm-4 col-form-label text-right']) !!}
						<div class="col-8">
							{!! Form::select('level3', [null=>'Select'], '', ['class' => 'form-control']) !!}
						</div>
					</div>
				</div>

				<!-- yes org -->

				<div class="form-group">
					<div class="form-check col-sm-10 offset-sm-1">
					  {{ Form::radio('r', 'fiscal_agent' , false, ['class' => 'form-check-input js-fiscal-type']) }}
                      <label class="form-check-label"><h5>Yes, my organization has a fiscal sponsor.</h5></label>
                    </div>
				</div>

				<div id="fiscal_agent_section" class="js-agent-section-type" style="display: none;">
					<div class="form-group row">
						{!! Form::label('fiscal_sponsor', 'Enter organization name', ['class' => 'col-sm-4 col-form-label text-right']) !!}
						<div class="col-sm-8">
							{!! Form::text('fiscal_sponsor', null, ['class' => 'form-control']) !!}
						</div>
					</div>
				</div>

				<div class="form-group row mb-5">
					<label class="col-sm-4 col-form-label text-right">
					If you have a Guidestar URL, please provide it here
					<img src="https://demo.sageite.com/images/guidestar_logo.gif" alt="GuideStar" width="178" height="36">
					</label>
					<div class="col-8">
						{!! Form::text('guidestar_url', null, ['class' => 'form-control']) !!}
					</div>
				</div>
	        </div>
	        <div class="clearfix"></div>
	    </div>
	</div>
<div class="card-footer">
	{!! Form::submit('Save', ['name' => 'save', 'id' =>'id_save_btn', 'class' => 'btn btn-accent']) !!}
	{!! Form::submit('Save/Continue', ['name' => 'save-continue', 'class' => 'btn btn-accent ml-2']) !!}
    <button type="submit" class="btn btn-default float-right">Cancel</button>
</div>
{!! Form::close() !!}