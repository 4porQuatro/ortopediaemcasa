<div class="container">
    <div aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ urli18n() }}">Ortopedia</a></li>
                @if(!empty($crumbs))
                    @foreach($crumbs as $name => $link)
                        <li class="breadcrumb-item"><a{!! (!empty($link)) ? ' href="' . $link . '"' : '' !!}>{{ $name }}</a></li>
                    @endforeach
                @endif
          </ol>
    </div>
</div>
