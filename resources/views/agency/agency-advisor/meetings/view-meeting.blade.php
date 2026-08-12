<?php
$pageTitle = 'View-Meeting';
$meetings = [
    [
        'title' => 'Project Kickoff',
        'start_time' => '2024-04-10 09:00:00',
        'end_time' => '2024-04-10 10:00:00',
        'attendees' => [
            ['email' => 'john@example.com'],
            ['email' => 'jane@example.com'],
            ['email' => 'alice@example.com'],
        ],
        'link' => 'https://example.com/meeting/1',
    ],
    [
        'title' => 'Weekly Standup',
        'start_time' => '2024-04-12 10:00:00',
        'end_time' => '2024-04-12 11:00:00',
        'attendees' => [
            ['email' => 'bob@example.com'],
            ['email' => 'susan@example.com'],
            ['email' => 'david@example.com'],
        ],
        'link' => 'https://example.com/meeting/2',
    ],
];
?>
@extends(\App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section('content')
    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '12'])
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-8 col-r-15">
                    <div class="form-make-grant gn-form">
                        <div class="form-wrapper form-last">
                            <div class="row">
                                <div class="col-xl-12 col-r-15">
                                    <h2>Meeting Details</h2>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span></span>
                                        <a href="{{route('create.meeting')}}" class="btn btn-accent btn-sm">
                                            Create Meeting
                                        </a>
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Attendees</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($meetings as $meeting)
                                            <tr>
                                                <td>{{ $meeting['title'] }}</td>
                                                <td>{{ $meeting['start_time'] }}</td>
                                                <td>{{ $meeting['end_time'] }}</td>
                                                <td>
                                                    <ul>
                                                        @foreach($meeting['attendees'] as $attendee)
                                                        <li>{{ $attendee['email'] }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="{{ $meeting['link'] }}" class="btn btn-primary btn-sm mr-2" target="_blank">Go to Meeting</a>
                                                        <button class="btn btn-primary btn-sm copy-link" data-link="{{ $meeting['link'] }}">Copy Link</button>
                                                    </div>
                                                </td>
                                                
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('agency.agency-advisor.common-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('.copy-link').click(function(){
            var link = $(this).data('link');
            var tempInput = $("<input>");
            $("body").append(tempInput);
            tempInput.val(link).select();
            document.execCommand("copy");
            tempInput.remove();
            alert('Link copied to clipboard');
        });
    });
</script>
