@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@endif

{{-- Intro Lines --}}
<div class="text-center">
@foreach ($introLines as $line)
{!! $line !!}
@endforeach
</div>

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}
@endforeach

{{-- Salutation --}}
<div class="text-center">
@if (! empty($salutation))
{{ $salutation }}
@endif
</div>

{{-- Subcopy --}}
@isset($actionText)
<div class="text-center">
@component('mail::subcopy')
@lang(
    'app.mail-footer',
    [
        'actionText' => $actionText,
        'actionUrl' => $actionUrl
    ]
)
@endcomponent
</div>
@endisset
@endcomponent
