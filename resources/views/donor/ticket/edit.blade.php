<?php
$pageTitle = "Edit Ticket";
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section ('content')
    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '12'])
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-8 col-r-15">
                    <div class="form-make-grant gn-form">
                        <form method="POST" action="{{ route('ticket.update.donor', ['ticket_id' => $ticket->id]) }}" accept-charset="UTF-8" id="grant-form">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                
                                <div id="id_change_form_layout" class="col-sm-11">

                                    <div class="form-group row">
                                        <label for="fund" class="col-sm-3 col-form-label text-right pr-0">Fund Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" value="{{ $contactFunds[$fund_id] }}" readonly>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                                        <label for="title" class="col-sm-3 col-form-label text-right pr-0">Subject *</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $ticket->title) }}" required>
                                        </div>
                                        
                                        @if($errors->has('title'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('title') }}
                                            </em>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group row {{ $errors->has('description') ? 'has-error' : '' }}">
                                        <label for="content" class="col-sm-3 col-form-label text-right pr-0">Description</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" rows="4" id="content" placeholder="Description.." name="content" cols="50">
                                                {{ old('content', $ticket->description) }}
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

                                    <div class="form-group row {{ $errors->has('status_id') ? 'has-error' : '' }}">
                                        <label for="status" class="col-sm-3 col-form-label text-right pr-0">Status*</label>
                                        <div class="col-sm-9">
                                            <select name="status_id" id="status" class="form-control" required>
                                                <option value="0">Select Status</option>
                                                @foreach($statusDropdown as $id => $status)
                                                    {{-- <option value="{{ $id }}">{{ $status }}</option> --}}
                                                    <option value="{{ $id }}" {{ ($ticket->status == $id) ? 'selected' : '' }}>
                                                        {{ $status }}
                                                    </option>
                                                @endforeach
                                                
                                            </select>
                                            @if($errors->has('status_id'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('status_id') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row {{ $errors->has('priority_id') ? 'has-error' : '' }}">
                                        <label for="priority" class="col-sm-3 col-form-label text-right pr-0">Priority*</label>
                                        <div class="col-sm-9">
                                            <select name="priority_id" id="priority" class="form-control" required>
                                                <option value="0">Select Priority</option>
                                                @foreach($priorityDropdown as $id => $priority)
                                                    {{-- <option value="{{ $id }}">{{ $priority }}</option> --}}
                                                    <option value="{{ $id }}" {{ ($ticket->priority == $id) ? 'selected' : '' }}>
                                                        {{ $priority }}
                                                    </option>
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
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">Category*</label>
                                        <div class="col-sm-9">
                                            <select name="category_id" id="category" class="form-control select2" required>
                                                <option value="0">Select Category</option>
                                                @foreach($categoryDropdown as $id => $category)
                                                    {{-- <option value="{{ $id }}">{{ $category }}</option> --}}
                                                    <option value="{{ $id }}" {{ ($ticket->category == $id) ? 'selected' : '' }}>
                                                        {{ $category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('category_id'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('category_id') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- <div class="form-group row {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                        <label for="donor" class="col-sm-3 col-form-label text-right pr-0">Donor*</label>
                                        <div class="col-sm-9">
                                            <select name="donor_id" id="donor_id" class="form-control select2" required>
                                                <option value="0">Select Donor</option>
                                            </select>
                                            
                                            @if($errors->has('category_id'))
                                                <em class="invalid-feedback">
                                                    {{ $errors->first('category_id') }}
                                                </em>
                                            @endif
                                        </div>
                                    </div> --}}

                                    
                                    {{-- <div class="form-group row {{ $errors->has('author_name') ? 'has-error' : '' }}">
                                        <label for="author_name" class="col-sm-3 col-form-label text-right pr-0">Author Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="author_name" name="author_name" class="form-control" value="{{ old('author_name', isset($ticket) ? $ticket->author_name : '') }}">
                                        </div>
                                        @if($errors->has('author_name'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('author_name') }}
                                            </em>
                                        @endif
                                    </div>

                                    <div class="form-group row {{ $errors->has('author_email') ? 'has-error' : '' }}">
                                        <label for="author_email" class="col-sm-3 col-form-label text-right pr-0">Author Email</label>
                                        <div class="col-sm-9">
                                            <input type="text" id="author_email" name="author_email" class="form-control" value="{{ old('author_email', isset($ticket) ? $ticket->author_email : '') }}">
                                        </div>
                                        @if($errors->has('author_email'))
                                            <em class="invalid-feedback">
                                                {{ $errors->first('author_email') }}
                                            </em>
                                        @endif
                                    </div> --}}

                                    {{-- <div class="form-group row" id="staticDataDropdown" style="display: ;">
                                        <div class="offset-md-3 col-md-9 xs-mt-2">
                                            <label for="staticData" class="text-right">Donor Contact</label>
                                            <div id="donor_list_div">
                                                
                                            </div>
                                        </div>
                                    </div> --}}
                                     
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <div class="offset-sm-3 col-sm-5 col-md-4">
                                    <input name="save" id="id_save_btn" class="btn btn-accent btn-sm w100" type="submit" value="Update">
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
    
</script>






