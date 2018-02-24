{!! Form::open(['action' => 'Store\WishlistController@create', 'class' => 'add-to-wishlist-form']) !!}
    {!! Form::input('hidden', 'item_id', $item->id) !!}

    <button
            type="submit"
            class="btn-icon{{ (auth()->check() && auth()->user()->items->contains($item)) ? ' active' : '' }}"
            data-toggle="wishlist-tooltip"
            data-placement="bottom"
            title="@lang('app.add-to-wishlist')"
    >
        <i class="zmdi zmdi-favorite"></i>
    </button>
{!! Form::close() !!}

@push('scripts')
    <script>
        $(function () {
            $('[data-toggle="wishlist-tooltip"]').tooltip()
        })
    </script>
@endpush
