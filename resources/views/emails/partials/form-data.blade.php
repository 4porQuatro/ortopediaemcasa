@if(!empty($values))
<hr>
<p style="text-align: center;">
@foreach($values as $key=>$value)
    <b>@lang('app.' . $key):</b> {{ $value }}<br>
@endforeach
</p>
@endif
