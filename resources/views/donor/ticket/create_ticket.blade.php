<?php
$pageTitle = "Create Ticket";
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section ('content')
    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '12'])
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-8 col-r-15">
                    <div class="form-make-grant gn-form">
                        <form method="POST" action="{{ route('donor.tickets.store') }}" accept-charset="UTF-8" id="grant-form">
                            @csrf

                            <div class="row">
                                
                                <div id="id_change_form_layout" class="col-sm-11">

                                    <div class="form-group row">
                                        <label for="fund" class="col-sm-3 col-form-label text-right pr-0">Fund Name</label>
                                        <div class="col-sm-9">
                                            <select id="id_fund_id" class="form-control" name="fund_id" onchange = "getAdvisorlist();">
                                                <option value="0">Select Fund</option>
                                                @foreach($contactFunds as $fund => $val)

                                                    <option value="{{ $fund }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="fund" class="col-sm-3 col-form-label text-right pr-0">Ticket Type*</label>
                                        <div class="col-sm-9">
                                            <select name="category" id="category" class="form-control" required>
                                                <option value="0">Select Ticket Type</option>
                                                @foreach($categoryDropdown as $id => $category)
                                                    <option value="{{ $id }}">{{ $category }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if($errors->has('category_id'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('category_id') }}
                                            </em>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                                        <label for="title" class="col-sm-3 col-form-label text-right pr-0">Subject *</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', isset($ticket) ? $ticket->title : '') }}" required placeholder="Subject..">
                                        </div>
                                        
                                        @if($errors->has('title'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('title') }}
                                            </em>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group row {{ $errors->has('content') ? 'has-error' : '' }}">
                                        <label for="content" class="col-sm-3 col-form-label text-right pr-0">Description</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" rows="4" id="content" placeholder="Description.." name="content" cols="50">
                                                {{ old('content', isset($ticket) ? $ticket->description : '') }}
                                            </textarea>
                                            <script>
                                                ClassicEditor
                                                    .create( document.querySelector( '#content' ) )
                                                    .catch( error => {
                                                        console.error( error );
                                                    } );
                                            </script>                                            
                                        </div>
                                        @if($errors->has('description'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('description') }}
                                            </em>
                                        @endif
                                    </div>

                                    <div class="form-group row {{ $errors->has('priority_id') ? 'has-error' : '' }}">
                                        <label for="priority" class="col-sm-3 col-form-label text-right pr-0">Priority*</label>
                                        <div class="col-sm-9">
                                            <select name="priority" id="priority" class="form-control" required>
                                                <option value="0">Select Priority</option>
                                                @foreach($priorityDropdown as $id => $priority)
                                                    <option value="{{ $id }}">{{ $priority }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('priority_id'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('priority_id') }}
                                                </em>
                                            @endif                               
                                        </div>
                                    </div>

                                    <div class="form-group row {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                        <label for="donor" class="col-sm-3 col-form-label text-right pr-0">Advisor*</label>
                                        <div class="col-sm-9">
                                            <select name="advisor_id" id="advisor_id" class="form-control" required>
                                                <option value="0">Select Advisor</option>
                                            </select>
                                            
                                            @if($errors->has('advisor_id'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('category_id') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <div class="offset-sm-3 col-sm-5 col-md-4">
                                    <input name="save" id="id_save_btn" class="btn btn-accent btn-sm w100" type="submit" value="Submit">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>

<script>
    function getAdvisorlist() {
        var selectedValue = $("#id_fund_id").val();

        $.ajax({
            type: 'GET',
            url: "/m/ticket/advisor-list",
            data: { 'fundId': selectedValue },
            success: function (response) {
                // Handle the JSON response and construct HTML on the client side
                var advisorList = response.advisorList;
                var  html = '<option value="0">Select Advisor</option>';

                if (advisorList.length > 0) {
                    advisorList.forEach(function (advisor) {
  
                        html += '<option value="' + advisor.contact_id +'">' + advisor.first_name +" "+ advisor.last_name +'</option>';
                    });
                }
                $("#advisor_id").html(html);
            }
        });
    } 
</script>






