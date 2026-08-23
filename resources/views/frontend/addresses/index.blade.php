@extends('frontend.layouts.account')

@section('title', 'My Addresses')

@section('account')
    <div class="account-card">
        <div class="account-card__header">
            <h2>My Addresses</h2>
            <a href="{{ route('addresses.create') }}" class="btn btn-primary btn-sm btn-auto-sm">
                <i class="fas fa-plus"></i> Add New Address
            </a>
        </div>
        <div class="account-card__body">
                    @if($addresses->count() > 0)
                        <div class="account-address-grid">
                            @foreach($addresses as $address)
                                <div class="card account-address-card h-100 {{ $address->default_shipping || $address->default_billing ? 'border-primary' : '' }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">{{ $address->name }}</h6>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ route('addresses.show', $address->id) }}"><i class="fas fa-eye"></i> View</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('addresses.edit', $address->id) }}"><i class="fas fa-edit"></i> Edit</a></li>
                                                        @if(!$address->default_shipping)
                                                            <li>
                                                                <form action="{{ route('addresses.set-default-shipping', $address->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item"><i class="fas fa-shipping-fast"></i> Set as Default Shipping</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if(!$address->default_billing)
                                                            <li>
                                                                <form action="{{ route('addresses.set-default-billing', $address->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item"><i class="fas fa-credit-card"></i> Set as Default Billing</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if(!($address->default_billing && $address->default_shipping))
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this address?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash"></i> Delete</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            @if($address->default_shipping || $address->default_billing)
                                                <div class="mb-2">
                                                    @if($address->default_shipping)
                                                        <span class="badge bg-primary me-1">Default Shipping</span>
                                                    @endif
                                                    @if($address->default_billing)
                                                        <span class="badge bg-success">Default Billing</span>
                                                    @endif
                                                </div>
                                            @endif

                                            <p class="card-text mb-1"><strong>Email:</strong> {{ $address->email }}</p>
                                            <p class="card-text mb-1"><strong>Phone:</strong> {{ $address->phone }}</p>
                                            <p class="card-text mb-1"><strong>Type:</strong> <span class="badge bg-secondary">{{ ucfirst($address->type) }}</span></p>
                                            <p class="card-text mb-1"><strong>Area:</strong> {{ ($address->delivery_area ?? 'inside') === 'outside' ? 'Outside' : 'Inside' }}</p>
                                            <p class="card-text mb-2"><strong>Address:</strong> {{ $address->address }}</p>
                                            <p class="card-text mb-0">
                                                <small class="text-muted">Bangladesh</small>
                                            </p>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    @else
                        <div class="account-empty">
                            <div class="account-empty__icon"><i class="fas fa-map-marker-alt"></i></div>
                            <h5>No addresses found</h5>
                            <p>You haven't added any addresses yet.</p>
                            <a href="{{ route('addresses.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Your First Address
                            </a>
                        </div>
                    @endif
        </div>
    </div>
@endsection

@push('account-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.dropdown-toggle').forEach(function (el) {
            bootstrap.Dropdown.getOrCreateInstance(el);
        });
    });
</script>
@endpush