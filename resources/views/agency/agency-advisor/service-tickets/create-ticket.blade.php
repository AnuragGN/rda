@extends('agency.layouts.main')
@section('content')
@include('common.page-header', ['pageTitle' => 'Create Ticket', 'hcXlWidth' => '12'])

<div class="container">
    <div class="form-wrapper form-last">
        <div class="row">
            <div class="col-xl-8 col-r-15">
                <div class="form-make-grant gn-form">

                    <form method="POST" action="{{ route('tickets.store') }}" accept-charset="UTF-8" id="grant-form">
                        @csrf

                        {{-- Validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div id="id_change_form_layout" class="col-sm-11">

                                {{-- Sponsor --}}
                                <div class="form-group row">
                                    <label for="id_charity_id" class="col-sm-3 col-form-label text-right pr-0">
                                        Sponsor Name
                                    </label>
                                    <div class="col-sm-9">
                                        <select id="id_charity_id" class="form-control" name="charity_id"
                                            onchange="getFundsByCharity();">
                                            <option value="0">Select Sponsor</option>
                                            @foreach ($sponsors as $sponsor)
                                                <option value="{{ $sponsor['id'] }}">{{ $sponsor['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Fund --}}
                                <div class="form-group row">
                                    <label for="id_fund_id" class="col-sm-3 col-form-label text-right pr-0">
                                        Fund Name
                                    </label>
                                    <div class="col-sm-9">
                                        <select id="id_fund_id" class="form-control" name="fund_id"
                                            onchange="getDonorEmails();">
                                            <option value="0">Select Fund</option>
                                            @foreach ($contactFunds as $fund => $val)
                                                <option value="{{ $fund }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Ticket Type --}}
                                <div class="form-group row {{ $errors->has('category') ? 'has-error' : '' }}">
                                    <label for="category" class="col-sm-3 col-form-label text-right pr-0">
                                        Ticket Type <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select name="category" id="category" class="form-control" required>
                                            <option value="0">Select Ticket Type</option>
                                            @foreach ($categoryDropdown as $id => $category)
                                                <option value="{{ $id }}" @selected(old('category') == $id)>
                                                    {{ $category }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <em class="invalid-feedback d-block">{{ $message }}</em>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Title --}}
                                <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label for="title" class="col-sm-3 col-form-label text-right pr-0">
                                        Title <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" id="title" name="title" class="form-control"
                                            value="{{ old('title', $ticket->title ?? '') }}" required>
                                        @error('title')
                                            <em class="invalid-feedback d-block">{{ $message }}</em>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="form-group row {{ $errors->has('content') ? 'has-error' : '' }}">
                                    <label for="content" class="col-sm-3 col-form-label text-right pr-0">
                                        Description
                                    </label>
                                    <div class="col-sm-9 question-card" data-limit="300 words">
                                        <textarea class="summernote form-control answer" rows="4" id="content"
                                            name="content" placeholder="Description..">{{ old('content') }}</textarea>
                                        <span class="question-actions">
                                            <span class="ai-action" data-type="draft_answer" title="Generate AI Draft">
                                                <i class="fas fa-pen-fancy"></i> 
                                            </span>
                                            <span class="ai-action" data-type="polish" title="Improve with AI">
                                                <i class="fas fa-magic"></i>
                                            </span>
                                            <span class="ai-action" data-type="translate" title="Translate with AI">
                                                <i class="fas fa-globe"></i>
                                            </span>
                                            
                                        </span>
                                        <div class="ai-status"></div>
                                        @error('content')
                                            <em class="invalid-feedback d-block">{{ $message }}</em>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Priority --}}
                                <div class="form-group row {{ $errors->has('priority') ? 'has-error' : '' }}">
                                    <label for="priority" class="col-sm-3 col-form-label text-right pr-0">
                                        Priority <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select name="priority" id="priority" class="form-control" required>
                                            <option value="0">Select Priority</option>
                                            @foreach ($priorityDropdown as $id => $priority)
                                                <option value="{{ $id }}" @selected(old('priority') == $id)>
                                                    {{ $priority }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('priority')
                                            <em class="invalid-feedback d-block">{{ $message }}</em>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Donor --}}
                                <div class="form-group row {{ $errors->has('donor_id') ? 'has-error' : '' }}">
                                    <label for="donor_id" class="col-sm-3 col-form-label text-right pr-0">
                                        Donor
                                    </label>
                                    <div class="col-sm-9">
                                        <select name="donor_id" id="donor_id" class="form-control">
                                            <option value="0">Select Donor</option>
                                        </select>
                                        @error('donor_id')
                                            <em class="invalid-feedback d-block">{{ $message }}</em>
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                {{-- Submit --}}
                                <div class="form-group row">
                                    <label for="donor" class="col-sm-3 col-form-label text-right pr-0"></label>
                                    <div class="col-sm-4">
                                        <input name="save" id="id_save_btn" class="btn btn-accent w100" type="submit" value="Submit">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@include('agency.agency-advisor.common-script')
@endsection
