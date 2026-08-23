@extends('frontend.layouts.account')

@section('title', 'Edit Address')

@section('account')
    <div class="account-card">
        <div class="account-card__header">
            <h2>Edit Address</h2>
            <a href="{{ route('addresses.index') }}" class="btn btn-secondary btn-sm btn-auto-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="account-card__body account-form">
                    <form action="{{ route('addresses.update', $address->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $address->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $address->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $address->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Address Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select Address Type</option>
                                    <option value="home" {{ old('type', $address->type) == 'home' ? 'selected' : '' }}>Home</option>
                                    <option value="office" {{ old('type', $address->type) == 'office' ? 'selected' : '' }}>Office</option>
                                    <option value="other" {{ old('type', $address->type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" value="Bangladesh" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Delivery Area <span class="text-danger">*</span></label>
                                @php $area = old('delivery_area', $address->delivery_area ?? 'inside'); @endphp
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="delivery_area" id="areaInside" value="inside" {{ $area == 'inside' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="areaInside">Inside</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="delivery_area" id="areaOutside" value="outside" {{ $area == 'outside' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="areaOutside">Outside</label>
                                </div>
                                @error('delivery_area')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Full Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" placeholder="House, road, area, landmark" required>{{ old('address', $address->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                            <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Address
                            </button>
                        </div>
                    </form>
        </div>
    </div>
@endsection
