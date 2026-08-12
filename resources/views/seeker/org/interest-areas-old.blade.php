@extends ('seeker.layouts.main', ['container' => 'container'])
@section ('content')

	@include('seeker.common.page-header', ['pageTitle' => 'Organization'])

	<section class="content">
		<div class="container">
		    <div class="row">
		        <div class="col-12">
		            <div class="card card-info">
		            	<div class="card-header text-uppercase">Interest Areas</div>
					  	<div class="card-body">
					  		<div class="mb-4">
						  		<h4>Please select which areas best classify the areas in which your organization serves.</h4>
						  	</div>
					  		<div class="mb-4">
					  			<p class="text-lg">
					  				Organization "Interest Areas" will provide an overview of your organization and its intentions. Later, when you add your programs and funding areas, you will have the opportunity to be more specific.
					  			</p>
					  		</div>
							<div class="card card-info">
					            <div class="card-header">
					              <h3 class="card-title">Program/Interest Areas (Please only check 10 Areas)</h3>

					              <div class="card-tools">
					                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
					                  <i class="fas fa-minus"></i>
					                </button>
					              </div>
					            </div>
					            <div class="card-body" style="display: block;">

					            	<div class="row">

		  								<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="A110" name="interest_area_id" value="A110">
					                        	<label class="form-check-label">Health Care</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="A120" name="interest_area_id" value="A120">
					                        	<label class="form-check-label">Home Care and/or Assisted Living</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="A130" name="interest_area_id" value="A130">
					                        	<label class="form-check-label">Housing</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="A140" name="interest_area_id" value="A140">
					                        	<label class="form-check-label">Long Term Care</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="A150" name="interest_area_id" value="A150">
					                        	<label class="form-check-label">Meals on Wheels</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B230" name="interest_area_id" value="B230">
					                        	<label class="form-check-label">Advocacy-Arts, Culture</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B110" name="interest_area_id" value="B110">
					                        	<label class="form-check-label">Arts Centers or Presenters</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B120" name="interest_area_id" value="B120">
					                        	<label class="form-check-label">Arts Education and Outreach</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B130" name="interest_area_id" value="B130">
					                        	<label class="form-check-label">Audience Development</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B140" name="interest_area_id" value="B140">
					                        	<label class="form-check-label">Dance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B150" name="interest_area_id" value="B150">
					                        	<label class="form-check-label">Film and Video</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B160" name="interest_area_id" value="B160">
					                        	<label class="form-check-label">Literary Arts</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B170" name="interest_area_id" value="B170">
					                        	<label class="form-check-label">Media and Communication</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B180" name="interest_area_id" value="B180">
					                        	<label class="form-check-label">Museums</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B190" name="interest_area_id" value="B190">
					                        	<label class="form-check-label">Music</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B200" name="interest_area_id" value="B200">
					                        	<label class="form-check-label">Opera</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B240" name="interest_area_id" value="B240">
					                        	<label class="form-check-label">Organizational Effectiveness and Governance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B210" name="interest_area_id" value="B210">
					                        	<label class="form-check-label">Theater</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="B220" name="interest_area_id" value="B220">
					                        	<label class="form-check-label">Visual Arts</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="Z110" name="interest_area_id" value="Z110">
					                        	<label class="form-check-label">Child</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="D110" name="interest_area_id" value="D110">
					                        	<label class="form-check-label">Adoption</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="D180" name="interest_area_id" value="D180">
					                        	<label class="form-check-label">Advocacy-Children &amp; Family</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="D120" name="interest_area_id" value="D120">
					                        	<label class="form-check-label">Children and Youth Services</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="D130" name="interest_area_id" value="D130">
					                        	<label class="form-check-label">Domestic Violence</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="D140" name="interest_area_id" value="D140">
					                        	<label class="form-check-label">Early Childhood Development</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="D150" name="interest_area_id" value="D150">
					                        	<label class="form-check-label">Family Counseling</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="D160" name="interest_area_id" value="D160">
					                        	<label class="form-check-label">Foster Care</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="D190" name="interest_area_id" value="D190">
					                        	<label class="form-check-label">Organizational Effectiveness and Governance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="D170" name="interest_area_id" value="D170">
					                        	<label class="form-check-label">Parenting Education and Support</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="E160" name="interest_area_id" value="E160">
					                        	<label class="form-check-label">Advocacy-Community Dev</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="E120" name="interest_area_id" value="E120">
					                        	<label class="form-check-label">Community Organizing</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="E110" name="interest_area_id" value="E110">
					                        	<label class="form-check-label">Community and Neighborhood Development</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="E130" name="interest_area_id" value="E130">
					                        	<label class="form-check-label">Crime Prevention</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="E140" name="interest_area_id" value="E140">
					                        	<label class="form-check-label">Economic Development</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="E150" name="interest_area_id" value="E150">
					                        	<label class="form-check-label">Housing Support</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="E170" name="interest_area_id" value="E170">
					                        	<label class="form-check-label">Organizational Effectiveness and Governance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="G110" name="interest_area_id" value="G110">
					                        	<label class="form-check-label">Adult Education</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="G190" name="interest_area_id" value="G190">
					                        	<label class="form-check-label">Advocacy-Education</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="G120" name="interest_area_id" value="G120">
					                        	<label class="form-check-label">After School and Educational Support</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="G130" name="interest_area_id" value="G130">
					                        	<label class="form-check-label">Higher Education</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="G140" name="interest_area_id" value="G140">
					                        	<label class="form-check-label">Libraries</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="G200" name="interest_area_id" value="G200">
					                        	<label class="form-check-label">Organizational Effectiveness and Governance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="G150" name="interest_area_id" value="G150">
					                        	<label class="form-check-label">Primary Education</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="G160" name="interest_area_id" value="G160">
					                        	<label class="form-check-label">Professional Development</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="G170" name="interest_area_id" value="G170">
					                        	<label class="form-check-label">Scholarships</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="G180" name="interest_area_id" value="G180">
					                        	<label class="form-check-label">Secondary Education</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="H140" name="interest_area_id" value="H140">
					                        	<label class="form-check-label">Advocacy-Employment, Training</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="H110" name="interest_area_id" value="H110">
					                        	<label class="form-check-label">Job Creation</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="H120" name="interest_area_id" value="H120">
					                        	<label class="form-check-label">Job Placement and/or Retention</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="H130" name="interest_area_id" value="H130">
					                        	<label class="form-check-label">Job Training</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="H150" name="interest_area_id" value="H150">
					                        	<label class="form-check-label">Organizational Effectiveness and Governance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="I190" name="interest_area_id" value="I190">
					                        	<label class="form-check-label">Advocacy-Environment, Animals</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="I110" name="interest_area_id" value="I110">
					                        	<label class="form-check-label">Conservation and/or Restoration</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="I120" name="interest_area_id" value="I120">
					                        	<label class="form-check-label">Domestic Animal Welfare</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="I130" name="interest_area_id" value="I130">
					                        	<label class="form-check-label">Environmental Beautification</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="I140" name="interest_area_id" value="I140">
					                        	<label class="form-check-label">Environmental Education</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="I200" name="interest_area_id" value="I200">
					                        	<label class="form-check-label">Organizational Effectiveness and Governance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="I150" name="interest_area_id" value="I150">
					                        	<label class="form-check-label">Pollution Abatement and Control</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="I160" name="interest_area_id" value="I160">
					                        	<label class="form-check-label">Waste Reduction or Recycling</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="I170" name="interest_area_id" value="I170">
					                        	<label class="form-check-label">Wildlife Protection</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="I180" name="interest_area_id" value="I180">
					                        	<label class="form-check-label">Zoos</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K110" name="interest_area_id" value="K110">
					                        	<label class="form-check-label">AIDS</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K220" name="interest_area_id" value="K220">
					                        	<label class="form-check-label">Advocacy-Health</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K120" name="interest_area_id" value="K120">
					                        	<label class="form-check-label">Alcohol and/or Drug Abuse</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K130" name="interest_area_id" value="K130">
					                        	<label class="form-check-label">Community Clinics</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K140" name="interest_area_id" value="K140">
					                        	<label class="form-check-label">Dental Health</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K150" name="interest_area_id" value="K150">
					                        	<label class="form-check-label">Diseases, Disorders and Medical Disciplines</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K160" name="interest_area_id" value="K160">
					                        	<label class="form-check-label">Health Education and Promotion</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K170" name="interest_area_id" value="K170">
					                        	<label class="form-check-label">Hospitals</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K180" name="interest_area_id" value="K180">
					                        	<label class="form-check-label">Medical Research</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K190" name="interest_area_id" value="K190">
					                        	<label class="form-check-label">Mental Health</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K230" name="interest_area_id" value="K230">
					                        	<label class="form-check-label">Organizational Effectiveness and Governance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K200" name="interest_area_id" value="K200">
					                        	<label class="form-check-label">Referral and Crisis Intervention</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="K210" name="interest_area_id" value="K210">
					                        	<label class="form-check-label">Reproductive Health</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="M190" name="interest_area_id" value="M190">
					                        	<label class="form-check-label">Advocacy-Human Services</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="M110" name="interest_area_id" value="M110">
					                        	<label class="form-check-label">Chronically Ill or Hospice Programs</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="M120" name="interest_area_id" value="M120">
					                        	<label class="form-check-label">Developmentally Disabled</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="M130" name="interest_area_id" value="M130">
					                        	<label class="form-check-label">Financial Counseling and Support</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="M140" name="interest_area_id" value="M140">
					                        	<label class="form-check-label">Food and Nutrition Programs</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="M150" name="interest_area_id" value="M150">
					                        	<label class="form-check-label">Homeless Shelters and/or Services</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="M200" name="interest_area_id" value="M200">
					                        	<label class="form-check-label">Organizational Effectiveness and Governance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="M160" name="interest_area_id" value="M160">
					                        	<label class="form-check-label">Physically Disabled</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="M170" name="interest_area_id" value="M170">
					                        	<label class="form-check-label">Recreation</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="M180" name="interest_area_id" value="M180">
					                        	<label class="form-check-label">Safety and Disaster Relief</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="R130" name="interest_area_id" value="R130">
					                        	<label class="form-check-label">Advocacy-Peace, Social Justice</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="R110" name="interest_area_id" value="R110">
					                        	<label class="form-check-label">Civil Rights and Social Justice</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="R140" name="interest_area_id" value="R140">
					                        	<label class="form-check-label">Organizational Effectiveness and Governance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="R120" name="interest_area_id" value="R120">
					                        	<label class="form-check-label">Peace</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="U110" name="interest_area_id" value="U110">
					                        	<label class="form-check-label">Buddhist</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="U120" name="interest_area_id" value="U120">
					                        	<label class="form-check-label">Christian</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="U130" name="interest_area_id" value="U130">
					                        	<label class="form-check-label">Hindu</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="U140" name="interest_area_id" value="U140">
					                        	<label class="form-check-label">Islamic</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="U150" name="interest_area_id" value="U150">
					                        	<label class="form-check-label">Jewish</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="U160" name="interest_area_id" value="U160">
					                        	<label class="form-check-label">Protestant</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="U170" name="interest_area_id" value="U170">
					                        	<label class="form-check-label">Roman Catholic</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="V140" name="interest_area_id" value="V140">
					                        	<label class="form-check-label">Advocacy-Youth Development</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="V110" name="interest_area_id" value="V110">
					                        	<label class="form-check-label">Community Service</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="V120" name="interest_area_id" value="V120">
					                        	<label class="form-check-label">Mentoring</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="V150" name="interest_area_id" value="V150">
					                        	<label class="form-check-label">Organizational Effectiveness and Governance</label>
					                        </div>
					            		</div>

		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
					                        	<input type="checkbox" class="form-check-input" id="V130" name="interest_area_id" value="V130">
					                        	<label class="form-check-label">Youth Employment</label>
					                        </div>
					            		</div>			            		
					            	</div>

					            </div>
					            <!-- /.card-body -->
					        </div>


					        <div class="card card-info">
					            <div class="card-header">
					              <h3 class="card-title">Population served (Click all that apply)</h3>
					              <div class="card-tools">
					                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
					                  <i class="fas fa-minus"></i>
					                </button>
					              </div>
					            </div>
					            <div class="card-body">
					            	<div class="row">
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="All Populations"> 
		              							<label class="form-check-label">All Populations</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Infants and Toddlers (under 3)"> 
		          								<label class="form-check-label">Infants and Toddlers (under 3)</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Children (3 - 11)"> 
		              							<label class="form-check-label">Children (3 - 11)</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Youth (12 - 25)"> 
		              							<label class="form-check-label">Youth (12 - 25)</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Adults"> 
		              							<label class="form-check-label">Adults</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Elderly"> 
		              							<label class="form-check-label">Elderly</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Special Needs/Disabled"> 
		              							<label class="form-check-label">Special Needs/Disabled</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Low Income"> 
		              							<label class="form-check-label">Low Income</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Gay/Lesbian/Bisexual/Transgender"> 
		              							<label class="form-check-label">Gay/Lesbian/Bisexual/Transgender</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="African American"> 
		              							<label class="form-check-label">African American</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="American Indian"> 
		              							<label class="form-check-label">American Indian</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Asian"> 
		              							<label class="form-check-label">Asian</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Caucasian/European American"> 
		              							<label class="form-check-label">Caucasian/European American</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Hispanic/Latino"> 
		              							<label class="form-check-label">Hispanic/Latino</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Female"> 
		              							<label class="form-check-label">Female</label>
					                        </div>
					            		</div>
		            					<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="population_served_id" value="Male"> 
		              							<label class="form-check-label">Male</label>
					                        </div>
					            		</div>
					            	</div>
					            </div>
					        </div>

					        <div class="card card-info">
					            <div class="card-header">
					              <h3 class="card-title">Geographic Area (Click all that apply)</h3>
					              <div class="card-tools">
					                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
					                  <i class="fas fa-minus"></i>
					                </button>
					              </div>
					            </div>
					            <div class="card-body">
					            	<div class="row">
		                        		<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="geographic_area_id" value="Metro Milwaukee/Four County Area">  
		              							<label class="form-check-label">Metro Milwaukee/Four County Area</label>
					                        </div>
					            		</div>
		                        		<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="geographic_area_id" value="Milwaukee">  
		              							<label class="form-check-label">Milwaukee</label>
					                        </div>
					            		</div>
		                        		<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="geographic_area_id" value="Waukesha">  
		              							<label class="form-check-label">Waukesha</label>
					                        </div>
					            		</div>
		                        		<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="geographic_area_id" value="Ozaukee">  
		              							<label class="form-check-label">Ozaukee</label>
					                        </div>
					            		</div>
		                        		<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="geographic_area_id" value="Washington">  
		              							<label class="form-check-label">Washington</label>
					                        </div>
					            		</div>
		                        		<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="geographic_area_id" value="Wisconsin">  
		              							<label class="form-check-label">Wisconsin</label>
					                        </div>
					            		</div>
		                        		<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="geographic_area_id" value="National/Other States">  
		              							<label class="form-check-label">National/Other States</label>
					                        </div>
					            		</div>
		                        		<div class="col-12 col-md-6">
					                        <div class="form-check">
		              							<input type="checkbox" name="geographic_area_id" value="International">  
		              							<label class="form-check-label">International</label>
					                        </div>
					            		</div>
					            	</div>
					            </div>
					        </div>
					  	</div>
					  	<div class="card-footer">
                            <button type="submit" class="btn btn-accent">Save</button>
                            <button type="submit" class="btn btn-accent ml-2">Save/Continue</button>
                            <button type="submit" class="btn btn-default float-right">Cancel</button>
                        </div>
					</div>
		        </div>
		    </div>
		</div>
	</section>

@endsection




