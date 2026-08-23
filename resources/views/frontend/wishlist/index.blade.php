@extends('frontend.layouts.account')

@section('title', 'My Wishlist')

@section('account')
    <div class="account-page-header">
        <div>
            <h2>My Wishlist</h2>
            <p class="account-page-header__subtitle">Items you've saved for later</p>
        </div>
        <a href="{{ route('products') }}" class="btn btn-outline-primary btn-auto-sm">
            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
        </a>
    </div>

    @if($wishlistItems && $wishlistItems->count() > 0)
        <div class="account-wishlist-grid">
            @foreach($wishlistItems as $item)
                <div class="account-card h-100">
                    <div class="position-relative">
                        @if(isset($item->product) && $item->product->thumb_image)
                            <img src="{{ asset($item->product->thumb_image) }}" class="w-100" alt="{{ $item->product->name ?? 'Product' }}" style="height: 200px; object-fit: cover; border-radius: 16px 16px 0 0;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px; border-radius: 16px 16px 0 0;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeFromWishlist({{ $item->id }})" aria-label="Remove from wishlist">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="account-card__body d-flex flex-column">
                        <h6 class="mb-2">{{ $item->product->name ?? 'Product Name' }}</h6>
                        <p class="text-muted small flex-grow-1 mb-2">{{ Str::limit($item->product->short_description ?? 'No description available', 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h6 text-primary mb-0">${{ number_format($item->product->price ?? 0, 2) }}</span>
                            @if(isset($item->product->offer_price) && $item->product->offer_price > 0)
                                <small class="text-muted"><del>${{ number_format($item->product->offer_price, 2) }}</del></small>
                            @endif
                        </div>
                        @if(isset($item->product))
                            <a href="{{ route('product-detail', $item->product->slug ?? '#') }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye"></i> View Product
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
                    <h4>Your wishlist is empty</h4>
                    <p>Save items you love to your wishlist and shop them later.</p>
                    <a href="{{ route('products') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('account-scripts')
<script>
function removeFromWishlist(itemId) {
    if (confirm('Are you sure you want to remove this item from your wishlist?')) {
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
                alert('Error removing item from wishlist');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing item from wishlist');
        });
    }
}
</script>
@endpush
