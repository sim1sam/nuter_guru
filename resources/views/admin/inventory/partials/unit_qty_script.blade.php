<script>
(function () {
    function updateQtyUnit(selectEl, qtyInput, unitEls) {
        if (!selectEl || !qtyInput) return;
        var opt = selectEl.options[selectEl.selectedIndex];
        var unit = (opt && opt.getAttribute('data-unit')) ? opt.getAttribute('data-unit') : 'PCS';
        var isKg = unit === 'KG';
        qtyInput.setAttribute('step', isKg ? '0.001' : '1');
        var allowZero = qtyInput.hasAttribute('data-allow-zero');
        qtyInput.setAttribute('min', allowZero ? '0' : (isKg ? '0.001' : '1'));
        (unitEls || []).forEach(function (el) {
            if (el) el.textContent = unit;
        });
    }

    function bindWrap(wrap) {
        var select = wrap.querySelector('[data-unit-product]');
        var qty = wrap.querySelector('[data-unit-input]');
        var labels = wrap.querySelectorAll('[data-unit-label]');
        if (!select || !qty) return;

        function refresh() {
            updateQtyUnit(select, qty, labels);
        }

        select.addEventListener('change', refresh);
        if (window.jQuery) {
            jQuery(select).on('select2:select select2:clear change', refresh);
        }
        refresh();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-unit-qty]').forEach(bindWrap);
    });
})();
</script>
