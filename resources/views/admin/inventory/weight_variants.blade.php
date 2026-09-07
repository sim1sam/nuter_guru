@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Weight Variants')}}</title>
@endsection
@section('admin-content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{__('admin.Weight Variants')}}</h1>
        </div>
        <div class="section-body">
            <p class="text-muted mb-3">
                {{__('admin.Global weight packs used by KG products. Stock always stays in KG.')}}
                <br>
                <strong>Examples:</strong> 100g, 200g, 250g, 500g, 750g, 1kg, 2kg, 5kg, 10kg — add any size you need.
            </p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h4>{{__('admin.Add New')}} Weight Variant</h4></div>
                        <div class="card-body">
                            <form action="{{ route('admin.weight-variant.store') }}" method="POST" id="addWeightVariantForm">
                                @csrf
                                <div class="form-group">
                                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="wv_name" class="form-control" required placeholder="e.g. 100g / 2kg" value="{{ old('name') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Code')}} <small class="text-muted">(optional)</small></label>
                                    <input type="text" name="code" id="wv_code" class="form-control" placeholder="Auto from name" value="{{ old('code') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Weight in Gram')}} <span class="text-danger">*</span></label>
                                    <input type="number" name="weight_in_gram" id="wv_gram" class="form-control" required min="1" placeholder="e.g. 100" value="{{ old('weight_in_gram') }}">
                                    <small class="text-muted">KG preview: <strong id="wv_kg_preview">0.000</strong> KG</small>
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Sort Order')}}</label>
                                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', ($variants->max('sort_order') ?? 0) + 1) }}" min="0">
                                </div>
                                <div class="form-group">
                                    <label>{{__('admin.Status')}}</label>
                                    <select name="status" class="form-control">
                                        <option value="1">{{__('admin.Active')}}</option>
                                        <option value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary btn-block">{{__('admin.Save')}} New Variant</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header"><h4>All Weight Variants ({{ $variants->count() }})</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin.Name')}}</th>
                                            <th>{{__('admin.Code')}}</th>
                                            <th>KG</th>
                                            <th>Gram</th>
                                            <th>{{__('admin.Sort Order')}}</th>
                                            <th>{{__('admin.Status')}}</th>
                                            <th width="120">{{__('admin.Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($variants as $variant)
                                        <tr>
                                            <td colspan="7" class="p-2">
                                                <form action="{{ route('admin.weight-variant.update', $variant->id) }}" method="POST" class="row align-items-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="col-md-2 mb-1">
                                                        <input type="text" name="name" class="form-control form-control-sm" value="{{ $variant->name }}" required>
                                                    </div>
                                                    <div class="col-md-2 mb-1">
                                                        <input type="text" name="code" class="form-control form-control-sm" value="{{ $variant->code }}" required>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <input type="text" class="form-control form-control-sm" value="{{ number_format($variant->weight_in_kg, 3) }}" readonly>
                                                    </div>
                                                    <div class="col-md-2 mb-1">
                                                        <input type="number" name="weight_in_gram" class="form-control form-control-sm" value="{{ $variant->weight_in_gram }}" required min="1">
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $variant->sort_order }}" min="0">
                                                    </div>
                                                    <div class="col-md-2 mb-1">
                                                        <select name="status" class="form-control form-control-sm">
                                                            <option value="1" {{ $variant->status == 1 ? 'selected' : '' }}>{{__('admin.Active')}}</option>
                                                            <option value="0" {{ $variant->status == 0 ? 'selected' : '' }}>{{__('admin.Inactive')}}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 mb-1">
                                                        <button class="btn btn-sm btn-primary">{{__('admin.Update')}}</button>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDeleteWeightVariant({{ $variant->id }})"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No weight variants yet. Add one from the left form.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<form id="deleteWeightVariantForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
(function ($) {
    function updateKgPreview() {
        var grams = parseInt($('#wv_gram').val(), 10) || 0;
        $('#wv_kg_preview').text((grams / 1000).toFixed(3));
    }

    $('#wv_gram').on('input change', updateKgPreview);
    $('#wv_name').on('focusout', function () {
        if (!$('#wv_code').val()) {
            var slug = $(this).val().toString().toLowerCase().trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-');
            $('#wv_code').val(slug);
        }
        // If name like 100g / 2kg, try fill grams
        if (!$('#wv_gram').val()) {
            var name = $(this).val().toString().toLowerCase().replace(/\s+/g, '');
            var m = name.match(/^(\d+(?:\.\d+)?)(kg|g)$/);
            if (m) {
                var n = parseFloat(m[1]);
                var grams = m[2] === 'kg' ? Math.round(n * 1000) : Math.round(n);
                $('#wv_gram').val(grams);
                updateKgPreview();
            }
        }
    });
    updateKgPreview();
})(jQuery);

function confirmDeleteWeightVariant(id) {
    if (!confirm('Delete this weight variant?')) {
        return;
    }
    var form = document.getElementById('deleteWeightVariantForm');
    form.action = '{{ url('admin/weight-variant') }}/' + id;
    form.submit();
}
</script>
@endsection
