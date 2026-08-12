@extends ('seeker.layouts.main', ['container' => 'container'])

@section ('content')

	@include('seeker.common.page-header', ['pageTitle' => 'Organization Story'])

	<section class="content">
		<div class="container">
			<div class="row clearfix">
				<div class="col-12">
					<div class="card card-info">

						<div class="card-header text-uppercase">Organization Story</div>

							{!! Form::model($org, ['method' => 'POST', 'files' => false, 'route' => ['gs-org-organization-story-save'], 'id' => 'gs-org-organization-story', 'class'=>'form-horizontal']) !!}

							<div class="card-body">

								<div class="mb-4">
									<p class="text-lg">Please describe the mission, programs, history, and your organization's use of volunteers. Please provide a general description of your programs - you will have an opportunity to profile specific projects and funding needs later.   </p>
								</div>
								<div class="mb-4">
									<div class="row justify-content-between mb-2">
										<div class="col-5">
											<strong>Mission Statement</strong>
										</div>
										<div class="col-7 text-right">
											<em>
												You have used
												<b><span id="missionStatementLength">0</span></b>
												of
												<b>500</b>
											</em>
										</div>
									</div>

									{!! Form::textarea('mission', null, ['id' => 'mission-statement-note']) !!}

								</div>

								<div class="mb-4">
									<div class="row justify-content-between mb-2">
										<div class="col-5">
											<strong>Programs</strong>
										</div>
										<div class="col-7 text-right">
											<em>
												You have used
												<b><span id="programsLength">0</span></b>
												of
												<b>500</b>
											</em>
										</div>
									</div>
									{!! Form::textarea('programs', null, ['id' => 'programs-note']) !!}
								</div>

								<div class="mb-4">
									<div class="row justify-content-between mb-2">
										<div class="col-5">
											<strong>History</strong>
										</div>
										<div class="col-7 text-right">
											<em>
												You have used
												<b><span id="historyLength">0</span></b>
												of
												<b>500</b>
											</em>
										</div>
									</div>
									{!! Form::textarea('history', null, ['id' => 'history-note']) !!}
								</div>

								<div class="mb-4">
									<div class="row justify-content-between mb-2">
										<div class="col-5">
											<strong>Volunteerism</strong>
										</div>
										<div class="col-7 text-right">
											<em>
												You have used
												<b><span id="volunteerismLength">0</span></b>
												of
												<b>500</b>
											</em>
										</div>
									</div>
									{!! Form::textarea('volunteerism', null, ['id' => 'volunteerism-note']) !!}
								</div>
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
	</section>

	<script type="text/javascript">
		$(document).ready(function() {
			var maxLimit = 500;

			window.registerContentNote = function (element , maxChars, countIndicator){
				$(element).summernote({
					height: 200,
					callbacks: {
						onKeydown: function (e) {
							var t = e.currentTarget.innerText;
							if (t.trim().length >= maxChars) {
								//delete keys, arrow keys, copy, cut, select all
								if (e.keyCode != 8 && !(e.keyCode >=37 && e.keyCode <=40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey) && !(e.keyCode == 65 && e.ctrlKey))
									e.preventDefault();
							}
						},
						onKeyup: function (e) {
							var t = e.currentTarget.innerText;
							$(countIndicator).text(t.trim().length);
						},
						onPaste: function (e) {
							var t = e.currentTarget.innerText;
							var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
							e.preventDefault();
							var maxPaste = bufferText.length;
							if(t.length + bufferText.length > maxChars){
								maxPaste = maxChars - t.length;
							}
							if(maxPaste > 0){
								document.execCommand('insertText', false, bufferText.substring(0, maxPaste));
							}
							$(countIndicator).text(t.length);
						}
					}
				});
			};

			$(function(){
				registerContentNote('#mission-statement-note', maxLimit, '#missionStatementLength');
				registerContentNote('#programs-note', maxLimit, '#programsLength');
				registerContentNote('#history-note', maxLimit, '#historyLength');
				registerContentNote('#volunteerism-note', maxLimit, '#volunteerismLength');
			});


		});
	</script>

@endsection
