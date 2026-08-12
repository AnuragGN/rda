@extends('donor.emails.plain-text')

@section('content')

{{-- Logo --}}
@if(!empty($logoPath))
    <img src="{{ $message->embed(public_path($logoPath)) }}"
         alt="Client Logo"
         style="max-height:70px; margin-bottom:20px; display:block;">
@endif

<p>Dear {{ $adminName ?? 'Administrator' }},</p>

<p>
    An Advisor has successfully submitted a <strong>Donor-Advised Fund (DAF)</strong> application 
    on behalf of a donor. The application is now ready for administrative review and processing.
</p>

<p><strong>Application Summary</strong></p>

<ul style="margin-top:10px; margin-bottom:15px;">
    <li><strong>Donor Name:</strong> {{ $donorName ?? 'N/A' }}</li>
    <li><strong>Advisor Name:</strong> {{ $advisorName ?? $name ?? 'N/A' }}</li>
    <li><strong>DAF Application ID:</strong> {{ $dafId ?? 'N/A' }}</li>
    <li><strong>Submission Date:</strong> {{ $submittedAt ?? $created_at ?? 'N/A' }}</li>
</ul>

<p>
    If additional information is required, you may contact the Advisor at 
    <strong>{{ $advisorEmail ?? $email ?? 'the registered advisor email' }}</strong>.
</p>

<p>Regards, <br>Support Team</p>
<hr style="margin: 1rem 0; background-color: #ccc; height: 1px; border: 0;">
<p><small>This is a system-generated e-mail.</small></p>

@endsection
