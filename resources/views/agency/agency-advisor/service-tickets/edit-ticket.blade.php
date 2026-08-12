@extends(\App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section('content')
@include('common.page-header', ['pageTitle' => 'Edit Ticket', 'hcXlWidth' => '12'])

<div class="container">
    <div class="form-wrapper form-last">
        <div class="row">
            <div class="col-xl-8 col-r-15">
                <div class="form-make-grant gn-form">

                    <form method="POST"
                        action="{{ route('agency.ticket.update', ['ticket_id' => $ticket->id]) }}"
                        accept-charset="UTF-8" id="grant-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="source_page" value="{{ $page }}">

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

                                {{-- Fund Name (read-only) --}}
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label text-right pr-0">Fund Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control"
                                            value="{{ $contactFunds[$fund_id] ?? '' }}" readonly>
                                    </div>
                                </div>

                                {{-- Subject --}}
                                <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label for="title" class="col-sm-3 col-form-label text-right pr-0">
                                        Subject <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" id="title" name="title" class="form-control"
                                            placeholder="Subject"
                                            value="{{ old('title', $ticket->title) }}" required>
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
                                            name="content" placeholder="Description..">{{ old('content', $ticket->description) }}</textarea>
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

                                {{-- Ticket Status --}}
                                <div class="form-group row {{ $errors->has('status_id') ? 'has-error' : '' }}">
                                    <label for="status" class="col-sm-3 col-form-label text-right pr-0">
                                        Ticket Status <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select name="status_id" id="status" class="form-control" required>
                                            <option value="0">Select Ticket Status</option>
                                            @foreach ($statusDropdown as $id => $status)
                                                <option value="{{ $id }}"
                                                    @selected(old('status_id', $ticket->status) == $id)>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status_id')
                                            <em class="invalid-feedback d-block">{{ $message }}</em>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Ticket Priority --}}
                                <div class="form-group row {{ $errors->has('priority_id') ? 'has-error' : '' }}">
                                    <label for="priority" class="col-sm-3 col-form-label text-right pr-0">
                                        Ticket Priority <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select name="priority_id" id="priority" class="form-control" required>
                                            <option value="0">Select Ticket Priority</option>
                                            @foreach ($priorityDropdown as $id => $priority)
                                                <option value="{{ $id }}"
                                                    @selected(old('priority_id', $ticket->priority) == $id)>
                                                    {{ $priority }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('priority_id')
                                            <em class="invalid-feedback d-block">{{ $message }}</em>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Ticket Type --}}
                                <div class="form-group row {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                    <label for="category" class="col-sm-3 col-form-label text-right pr-0">
                                        Ticket Type <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-9">
                                        <select name="category_id" id="category" class="form-control select2" required>
                                            <option value="0">Select Ticket Type</option>
                                            @foreach ($categoryDropdown as $id => $category)
                                                <option value="{{ $id }}"
                                                    @selected(old('category_id', $ticket->category) == $id)>
                                                    {{ $category }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <em class="invalid-feedback d-block">{{ $message }}</em>
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                {{-- Submit --}}
                                 <div class="form-group row">
                                    <label for="donor" class="col-sm-3 col-form-label text-right pr-0"></label>
                                    <div class="col-sm-4">
                                        <button type="submit" id="id_save_btn" class="btn btn-accent w100">
                                            Submit
                                        </button>
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
