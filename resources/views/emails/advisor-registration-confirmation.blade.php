@extends('donor.emails.plain-text')

@section('content')

<img src="{{ $message->embed(public_path($logoPath)) }}"
     alt="Client Logo"
     style="max-height:70px; margin-bottom:20px; display:block;">

<p>Dear {{ $name }},</p>

<p>
    Thank you for applying for an advisor account. We have received your submission,
    and our team has started the review process.
</p>

<p><strong>What happens next</strong></p>

<ul>
    <li>Administrators will review the details you provided and verify any required information.</li>
    <li>If your request is approved, we will activate your account and notify you by email.</li>
    <li>
        If we require additional information, we will contact you at
        {{ $email ?? 'the email address you provided' }}.
    </li>
</ul>

<p>
    If you have questions or need help, contact our support team at
    <a href="mailto:{{ config('mail.from.address', 'support@yourdomain.com') }}">
        {{ config('mail.from.address', 'support@yourdomain.com') }}
    </a>.
</p>

<p>
    Thank you,<br>
    <strong>The Support Team</strong>
</p>

<hr style="margin:16px 0; border:0; border-top:1px solid #ccc;">

<p style="font-size:12px; color:#666;">
    This is an automated message — please do not reply to this email.
</p>

@endsection
