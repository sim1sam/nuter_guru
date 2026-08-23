@extends('frontend.layouts.account')

@section('title', 'Address Details')

@section('account')
    <div class="account-card">
        <div class="account-card__header">
            <h2>Address Details</h2>
            <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-sm-auto">
                <a href="{{ route('addresses.edit', $address->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('addresses.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="account-card__body">
            <div class="account-detail-grid">
                <div>
                    <h5 class="text-primary mb-3">{{ $address->name }}</h5>

                    @if($address->default_shipping || $address->default_billing)
                        <div class="mb-3">
                            @if($address->default_shipping)
                                <span class="badge bg-primary me-2">Default Shipping Address</span>
                            @endif
                            @if($address->default_billing)
                                <span class="badge bg-success">Default Billing Address</span>
                            @endif
                        </div>
                    @endif

                    <div class="account-info-grid">
                        <div><strong>Email</strong><br>{{ $address->email }}</div>
                        <div><strong>Phone</strong><br>{{ $address->phone }}</div>
                        <div><strong>Address Type</strong><br><span class="badge bg-secondary">{{ ucfirst($address->type) }}</span></div>
                        <div><strong>Delivery Area</strong><br>{{ ($address->delivery_area ?? 'inside') === 'outside' ? 'Outside' : 'Inside' }}</div>
                        <div class="col-span-full"><strong>Full Address</strong><br>{{ $address->address }}</div>
                        <div><strong>Country</strong><br>Bangladesh</div>
                    </div>
                </div>

                <div class="account-card">
                    <div class="account-card__header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="account-card__body d-grid gap-2">
                        @if(!$address->default_shipping)
                            <form action="{{ route('addresses.set-default-shipping', $address->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-shipping-fast"></i> Set as Default Shipping
                                </button>
                            </form>
                        @endif

                        @if(!$address->default_billing)
                            <form action="{{ route('addresses.set-default-billing', $address->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-credit-card"></i> Set as Default Billing
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('addresses.edit', $address->id) }}" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-edit"></i> Edit Address
                        </a>

                        @if(!($address->default_billing && $address->default_shipping))
                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this address?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="fas fa-trash"></i> Delete Address
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info mb-0 py-2">
                                <small><i class="fas fa-info-circle"></i> Default addresses cannot be deleted.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('account-styles')
<style>
.account-info-grid .col-span-full {
    grid-column: 1 / -1;
}
</style>
@endpush
