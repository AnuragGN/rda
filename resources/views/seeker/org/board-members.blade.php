@extends ('seeker.layouts.main', ['container' => 'container'])
@section ('content')
	@include('seeker.common.page-header', ['pageTitle' => 'Board Members'])

	<section class="content">
		<div class="container">
		    <div class="row">
				<div class="col-12">
		            <div class="card card-info">
		            	<div class="card-header text-uppercase">Board Members</div>
					  	{!! Form::model($org, ['method' => 'POST', 'files' => false, 'route' => ['gs-org-board-members-save'], 'id' => 'gs-org-board-members']) !!}
					  	<div class="card-body">
					  		<div class="mb-4">
						  		<h4>Please list your current board members, title and name.</h4>
						  	</div>
						  	<div class="mb-4">
						  		<div class="form-group form-check">
								    <input type="checkbox" class="form-check-input" id="upload_document">
								    <label class="form-check-label" for="upload_document">Uploaded to Documents</label>
								</div>
							</div>
							<div class="mb-4">
								<div class="row justify-content-between mb-2">
								    <div class="col-5">
								      <strong>Board Members</strong>
								    </div>
								    <div class="col-7 text-right">
									<span>
										<em>
											You have used 
											<b><span id="contentPostLength">0</span></b> 
											of 
											<b>500</b>
										</em>
									</span>
								    </div>
								</div>		
								{!! Form::textarea('board', null, ['id' => 'board-member-note']) !!}
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
    	  var maxChars = 500;
		  $('#board-member-note').summernote({
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
                    $('#contentPostLength').text(t.trim().length);
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
                    $('#contentPostLength').text(t.length);
                }
            }
		  });
		});
    </script>

@endsection



