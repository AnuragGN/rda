<?php
$pageTitle = "Edit Task";
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section ('content')
    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '10'])
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-8 col-r-15">
                    <div class="form-make-grant gn-form">
                        <form method="POST" action="{{ route('task.update', ['task_id' => $task->task_id]) }}" accept-charset="UTF-8" id="grant-form">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div id="id_change_form_layout" class="col-sm-11">
                                    <div class="form-group row">
                                        <label for="fund" class="col-sm-3 col-form-label text-right pr-0">Fund Name</label>
                                        <div class="col-sm-9">
                                            <select id="id_fund_id" class="form-control" name="fund_id" fdprocessedid="gyy8l">
                                                <option value="0">Select Fund</option>
                                                

                                                @foreach($contactFunds as $fund => $val)

                                                    <option value="{{ $fund }}" {{ ( $task->fund_id == $fund) ? 'selected' : '' }}>{{ $val }}</option>
                                                @endforeach
                                               
                                            </select>
                                            {{-- <input type="text" name="" id="" value="{{ $task-> }}"> --}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="fund" class="col-sm-3 col-form-label text-right pr-0">Task Type</label>
                                        <div class="col-sm-9">
                                           
                                            <select id="task_type_id" class="form-control" name="task_type_id" onchange="toggleEmailCheckbox()">
                                                <option value="0">Select Task Type</option>
                                                <option value="Event" {{ ( $task->task_type == 'Event') ? 'selected' : '' }}>Event</option>
                                                <option value="Meeting" {{ ( $task->task_type == 'Meeting') ? 'selected' : '' }}>Meeting</option>
                                                <option value="Notes" {{ ( $task->task_type == 'Notes') ? 'selected' : '' }}>Notes</option>
                                                <option value="Raise Cash" {{ ( $task->task_type == 'Raise Cash') ? 'selected' : '' }}>Raise Cash</option>
                                                <option value="Rebalance Portfolio" {{ ( $task->task_type == 'Rebalance Portfolio') ? 'selected' : '' }}>Rebalace Portfolio</option>
                                            </select>
                                            {{-- <input type="text" class="form-control" id="subject" placeholder="Subject.." name="subject" value="{{ $task->task_type }}" required> --}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">Subject</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="subject" placeholder="Subject.." name="subject" value="{{ $task->subject }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">Description</label>
                                        <div class="col-sm-9">
                                                <textarea class="form-control" rows="6" id="description" name="description" cols="50">{{ $task->description }}</textarea>
                                            <script>
                                                ClassicEditor
                                                    .create( document.querySelector( '#description' ) )
                                                    .catch( error => {
                                                        console.error( error );
                                                    } );
                                            </script>                                            
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">Start Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" id="start_date" placeholder="Start Date" name="start_date" value="{{ $task->start_date}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">End Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" id="end_date" placeholder="End Date" name="end_date" value="{{ $task->end_date }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="purpose_type" class="col-sm-3 col-form-label text-right pr-0">Task Priority</label>
                                        <div class="col-sm-9">
                                            <select id="task_priority" class="form-control" name="task_priority" onchange="">
                                                <option value="0">Select Task Priority</option>
                                                <option value="Low" {{ ( $task->task_priority == 'Low') ? 'selected' : '' }}>Low</option>
                                                <option value="Normal" {{ ( $task->task_priority == 'Normal') ? 'selected' : '' }}>Normal</option>
                                                <option value="High" {{ ( $task->task_priority == 'High') ? 'selected' : '' }}>High</option>
                                                <option value="Urgent" {{ ( $task->task_priority == 'Urgent') ? 'selected' : '' }}>Urgent</option>
                                                
                                            </select>
                                            {{-- <input type="text" class="form-control" id="task_priority" name="task_priority" value="{{ $task->task_priority }}"> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="offset-sm-3 col-sm-5 col-md-4">
                                    <input name="save" id="id_save_btn" class="btn btn-accent btn-sm w100" type="submit" value="Update">
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="{{route('agency-services')}}" class="cancel" onclick="">Cancel</a>
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

















