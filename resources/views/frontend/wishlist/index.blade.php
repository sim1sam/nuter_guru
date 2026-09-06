@extends('frontend.layouts.account')

@section('title', __('My Wishlist'))

@section('account')
    <div class="account-page-header">
        <div>
            <h2>{{ __('My Wishlist') }}</h2>
            <p class="account-page-header__subtitle">{{ __('Items you\'ve saved for later') }}</p>
        </div>
        <a href="{{ route('products') }}" class="btn btn-outline-primary btn-auto-sm">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Continue Shopping') }}
        </a>
    </div>

    @if($wishlistItems && $wishlistItems->count() > 0)
        <div class="account-wishlist-grid">
            @foreach($wishlistItems as $item)
                <div class="account-card h-100">
                    <div class="position-relative">
                        @if(isset($item->product) && $item->product->thumb_image)
                            <img src="{{ asset($item->product->thumb_image) }}" class="w-100" alt="{{ product_name($item->product) }}" style="height: 200px; object-fit: cover; border-radius: 16px 16px 0 0;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px; border-radius: 16px 16px 0 0;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeFromWishlist({{ $item->id }})" aria-label="{{ __('Remove from wishlist') }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="account-card__body d-flex flex-column">
                        <h6 class="mb-2">{{ product_name($item->product) }}</h6>
                        <p class="text-muted small flex-grow-1 mb-2">{{ Str::limit($item->product->short_description ?? __('No description available'), 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h6 text-primary mb-0">{{ format_currency($item->product->price ?? 0) }}</span>
                            @if(isset($item->product->offer_price) && $item->product->offer_price > 0)
                                <small class="text-muted"><del>{{ format_currency($item->product->offer_price) }}</del></small>
                            @endif
                        </div>
                        @if(isset($item->product))
                            <a href="{{ route('product-detail', $item->product->slug ?? '#') }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye"></i> {{ __('View Product') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if(method_exists($wishlistItems, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $wishlistItems->links() }}
            </div>
        @endif
    @else
        <div class="account-card">
            <div class="account-card__body">
                <div class="account-empty">
                    <div class="account-empty__icon"><i class="fas fa-heart"></i></div>
                    <h4>{{ __('Your wishlist is empty') }}</h4>
                    <p>{{ __('Save items you love to your wishlist and shop them later.') }}</p>
                    <a href="{{ route('products') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>{{ __('Start Shopping') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('account-scripts')
<script>
function removeFromWishlist(itemId) {
    if (confirm(@json(__('Are you sure you want to remove this item from your wishlist?')))) {
        fetch(`/user/wishlist/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(@json(__('Error removing item from wishlist')));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(@json(__('Error removing item from wishlist')));
        });
    }
}
</script>
@endpush
