@component('mail::message')
<div class="text-center">
{!! $email_message->message !!}
</div>

@include('emails.partials.form-data')
@endcomponent
