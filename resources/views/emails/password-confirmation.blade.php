@component('mail::message')
# Password Changed Successfully

Hello {{ $name }},

Your password has been successfully changed for your account ({{ $email }}) at {{ config('app.name') }}.

If you did not make this change, please contact us immediately to secure your account.

@component('mail::button', ['url' => route('login')])
Login to Your Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@if(isset($ip) && isset($timestamp))
@component('mail::subcopy')
This password change was made from IP address {{ $ip }} on {{ $timestamp }}.
@endcomponent
@endif
@endcomponent
