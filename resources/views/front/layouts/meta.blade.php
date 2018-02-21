@if(!empty($record))
    <meta name="description" content="{{ $record->description }}">
    <meta name="keywords" content="{{ $record->keywords }}">
    <title>{{ strip_tags($record->title) }} | {{ config('app.name') }}</title>
    <meta property="og:site_name" content="{{ strip_tags($record->title) }} | {{ config('app.name') }}">
    <meta property="og:title" content="{{ strip_tags($record->title) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:description" content="{{ strip_tags($record->description) }}">
    @if(!empty($record->getFirstImagePath($image_type)))
        <meta property="og:image" content="{{ $record->getFirstImagePath($image_type) }}">
    @endif
@endif
