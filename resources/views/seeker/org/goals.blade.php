@extends ('seeker.layouts.main', ['container' => 'container'])
@section ('content')
    @include('seeker.common.page-header', ['pageTitle' => 'Goals'])
    <section class="content">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="card card-info">
                        <div class="card-header text-uppercase">Goals</div>
        			  	<div class="card-body">
        			  		<div class="mb-4">
        				  		<p class="text-lg">Please describe the primary short-term goals of your organization.</p>
        				  	</div>
        					<div class="mb-4">
        						<div class="row justify-content-between mb-2">
        						    <div class="col-12 text-right">
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
        						<div id="goals-note">
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

    <script type="text/javascript">
    	$(document).ready(function() {
    	  var maxChars = 500;
		  $('#goals-note').summernote({
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



