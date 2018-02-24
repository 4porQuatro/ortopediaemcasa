<div class="page-header">
    @if(!empty($title))
        <h1 class="subsection__title{{ (!empty($text_center)) ? ' text-center' : '' }}">{{ $title }}</h1>
    @endif

    @if(!empty($subtitle))
        <h2 class="subsection__subtitle{{ (!empty($text_center)) ? ' text-center' : '' }}">{{ $subtitle }}</h2>
    @endif
</div>
