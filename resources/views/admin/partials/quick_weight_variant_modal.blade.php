{{-- Dynamic Weight Variant creator (used on product create/edit) --}}
<div class="modal fade" id="quickWeightVariantModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('admin.Add New')}} {{__('admin.Weight Variants')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="quickWvError" class="alert alert-danger d-none"></div>
                <div class="form-group">
                    <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                    <input type="text" id="quick_wv_name" class="form-control" placeholder="e.g. 100g, 750g, 2kg">
                </div>
                <div class="form-group">
                    <label>{{__('admin.Weight in Gram')}} <span class="text-danger">*</span></label>
                    <input type="number" id="quick_wv_gram" class="form-control" min="1" placeholder="e.g. 100">
                    <small class="text-muted">= <strong id="quick_wv_kg">0.000</strong> KG</small>
                </div>
                <div class="form-group">
                    <label>{{__('admin.Code')}} <small class="text-muted">(optional)</small></label>
                    <input type="text" id="quick_wv_code" class="form-control" placeholder="Auto">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('admin.Close') ?? 'Close'}}</button>
                <button type="button" class="btn btn-primary" id="quickWvSaveBtn">{{__('admin.Save')}}</button>
            </div>
        </div>
    </div>
</div>

<script>
(function ($) {
    function parseWeightFromName(name) {
        var m = (name || '').toLowerCase().replace(/\s+/g, '').match(/^(\d+(?:\.\d+)?)(kg|g)$/);
        if (!m) return null;
        var n = parseFloat(m[1]);
        return m[2] === 'kg' ? Math.round(n * 1000) : Math.round(n);
    }

    function updateQuickKg() {
        var g = parseInt($('#quick_wv_gram').val(), 10) || 0;
        $('#quick_wv_kg').text((g / 1000).toFixed(3));
    }

    $('#quick_wv_gram').on('input', updateQuickKg);
    $('#quick_wv_name').on('focusout', function () {
        var name = $(this).val();
        if (!$('#quick_wv_code').val()) {
            $('#quick_wv_code').val(name.toString().toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-'));
        }
        if (!$('#quick_wv_gram').val()) {
            var grams = parseWeightFromName(name);
            if (grams) {
                $('#quick_wv_gram').val(grams);
                updateQuickKg();
            }
        }
    });

    window.openQuickWeightVariantModal = function () {
        $('#quickWvError').addClass('d-none').text('');
        $('#quick_wv_name, #quick_wv_gram, #quick_wv_code').val('');
        updateQuickKg();
        $('#quickWeightVariantModal').modal('show');
    };

    function appendWeightVariantCard(variant) {
        var customMode = $('#sellingPriceMode').val() === 'custom';
        var html = ''
            + '<div class="col-md-4 mb-2" id="wv-card-' + variant.id + '">'
            + '  <label class="border rounded p-2 d-block mb-0 weight-variant-card">'
            + '    <input type="checkbox" name="weight_variant_ids[]" value="' + variant.id + '" class="weight-variant-check" data-kg="' + variant.weight_in_kg + '" data-name="' + variant.name + '" checked>'
            + '    <strong>' + variant.name + '</strong>'
            + '    <div class="small text-muted">' + Number(variant.weight_in_kg).toFixed(3) + ' KG</div>'
            + '    <div class="custom-price-wrap mt-1" style="' + (customMode ? '' : 'display:none;') + '">'
            + '      <input type="number" step="0.01" min="0" class="form-control form-control-sm weight-custom-price" name="weight_variant_prices[' + variant.id + ']" placeholder="{{__('admin.Custom selling price')}}">'
            + '    </div>'
            + '  </label>'
            + '</div>';
        $('#weightVariantCards').append(html);
        if (typeof renderWeightPreview === 'function') {
            renderWeightPreview();
        }
    }

    $('#quickWvSaveBtn').on('click', function () {
        var $btn = $(this);
        $btn.prop('disabled', true).text('Saving...');
        $('#quickWvError').addClass('d-none').text('');

        $.ajax({
            url: '{{ route('admin.weight-variant.store') }}',
            method: 'POST',
            dataType: 'json',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            data: {
                _token: '{{ csrf_token() }}',
                name: $('#quick_wv_name').val(),
                code: $('#quick_wv_code').val(),
                weight_in_gram: $('#quick_wv_gram').val(),
                status: 1
            },
            success: function (res) {
                if (res.success && res.variant) {
                    appendWeightVariantCard(res.variant);
                    $('#quickWeightVariantModal').modal('hide');
                    if (typeof toastr !== 'undefined') {
                        toastr.success(res.message || 'Weight variant created');
                    }
                } else {
                    $('#quickWvError').removeClass('d-none').text(res.message || 'Failed to save');
                }
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && (xhr.responseJSON.message || (xhr.responseJSON.errors && Object.values(xhr.responseJSON.errors)[0][0]))) || 'Failed to save weight variant';
                $('#quickWvError').removeClass('d-none').text(msg);
            },
            complete: function () {
                $btn.prop('disabled', false).text('{{__('admin.Save')}}');
            }
        });
    });
})(jQuery);
</script>
