@forelse ($wishlist as $item)
    {{ data_get($item,'title') }}
@empty
    none
@endforelse