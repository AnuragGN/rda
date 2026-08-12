<?php
$pageTitle = 'Create Meeting';
?>
@extends ( \App\Helpers\GnUtils::isDonorSession() ? 'donor.layouts.main' : 'agency.layouts.main')
@section('content')
    @include('common.page-header', ['pageTitle' => $pageTitle, 'hcXlWidth' => '12'])
    <div class="container">
        <div class="form-wrapper form-last">
            <div class="row">
                <div class="col-xl-8 col-r-15">
                    <div class="form-make-grant gn-form">
                        <!-- create-meeting.blade.php -->

                        <form method="post" action="#">
                            @csrf
                            <div class="form-group">
                                <label for="title">Meeting Title:</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="start">Start Time:</label>
                                <input type="datetime-local" name="start" id="start" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="end">End Time:</label>
                                <input type="datetime-local" name="end" id="end" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="timezone">Timezone:</label>
                                <select name="timezone" id="timezone" class="form-control" required>
                                    <option value="Asia/Kolkata">(GMT+05:30) Indian Standard Time</option>
                                    <option value="America/New_York">(GMT-04:00) Eastern Time (US & Canada)</option>
                                    <!-- Add more timezone options here -->
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="emails">Emails (comma-separated):</label>
                                <input type="text" name="emails" id="emails" class="form-control" required>
                            </div>

                            <!-- Add more fields as needed -->
                            <button type="submit" class="btn btn-primary">Create Meeting</button>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

@include('agency.agency-advisor.common-script')
