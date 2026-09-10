@php
    $qtyName = $qtyName ?? 'ordered_qty[]';
    $costName = $costName ?? 'unit_cost[]';
    $emptyText = $emptyText ?? trans('admin.Scan barcode or search a product to add');
    $categories = $categories ?? collect();
    $units = $units ?? \App\Models\Unit::activeUnits();
    $unitJson = $units->map(function ($u) {
        return [
            'code' => (string) $u->code,
            'name' => (string) $u->name,
            'is_base' => (bool) $u->is_base,
        ];
    })->values();
    $categoryNames = $categories->pluck('name', 'id');
    $productJson = $products->map(function ($p) use ($categoryNames) {
        $isKg = strtolower((string) ($p->unit_type ?? 'pcs')) === 'kg';
        $cost = (float) ($p->cost_price ?? 0);

        return [
            'id' => (int) $p->id,
            'name' => (string) $p->name,
            'sku' => (string) ($p->sku ?? ''),
            'barcode' => (string) ($p->barcode ?? ''),
            'cost' => $cost,
            'qty' => (float) $p->qty,
            'category_id' => $p->category_id ? (int) $p->category_id : null,
            'category' => $p->category_id ? (string) ($categoryNames[$p->category_id] ?? '') : '',
            'pcs_per_box' => max(1, (int) ($p->pcs_per_box ?? 1)),
            'purchase_unit' => \App\Models\Product::normalizeUnit($p->purchase_unit ?? 'pc'),
            'pack_unit_name' => \App\Models\Unit::label($p->purchase_unit ?? 'pc'),
            'unit_type' => $isKg ? 'kg' : 'pcs',
            'unit_label' => $isKg ? 'KG' : 'PCS',
            'qty_label' => format_stock_qty($p->qty, $p),
            'weight_variants' => [],
        ];
    })->values();
@endphp

<style>
.purchase-item-box { background:#f8f9fb; border:1px solid #e5e7eb; border-radius:8px; padding:16px; margin-bottom:18px; position:relative; }
.purchase-item-box .form-control { height: 44px; font-size: 15px; }
.purchase-filter-row { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:12px; align-items:flex-end; }
.purchase-filter-row .form-group { margin-bottom:0; flex:1; min-width:200px; }
.purchase-filter-row label { font-size:13px; font-weight:600; margin-bottom:4px; display:block; }
.purchase-search-wrap { position: relative; }
.purchase-search-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: var(--primary); font-size: 16px; pointer-events: none; z-index: 2;
}
#productScanSearchInput {
    padding-left: 42px; padding-right: 110px; border: 2px solid #d8dbe3;
}
#productScanSearchInput:focus { border-color: var(--primary); box-shadow: 0 0 0 0.15rem rgba(var(--primary-rgb), .2); }
.btn-add-item { min-width: 100px; height: 44px; font-weight: 600; }
.purchase-search-actions {
    position: absolute; right: 6px; top: 50%; transform: translateY(-50%); z-index: 3;
}
#purchaseSearchResults {
    position: absolute; left: 0; right: 0; top: calc(100% + 4px);
    background: #fff; border: 1px solid #d8dbe3; border-radius: 6px;
    max-height: 260px; overflow-y: auto; z-index: 9999; display: none;
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
}
#purchaseSearchResults .result-item {
    padding: 10px 14px; cursor: pointer; border-bottom: 1px solid #f0f0f0;
}
#purchaseSearchResults .result-item:last-child { border-bottom: 0; }
#purchaseSearchResults .result-item:hover,
#purchaseSearchResults .result-item.active { background: #f3f4f8; }
#purchaseSearchResults .result-name { font-weight: 600; color: #34395e; }
#purchaseSearchResults .result-meta { font-size: 12px; color: #6c757d; margin-top: 2px; }
#purchaseSearchResults .no-result { padding: 12px 14px; color: #6c757d; text-align: center; }
#purchaseItemsTable th, #purchaseItemsTable td { vertical-align: middle; }
#purchaseItemsTable td .form-control { min-width: 90px; }
#purchaseItemsTable .item-unit,
#purchaseItemsTable .item-weight-variant { min-width: 100px; height: 38px; }
#purchaseItemsTable .item-pcs-hint,
#purchaseItemsTable .item-cost-hint { display:block; font-size:11px; margin-top:3px; }
#purchaseItemsTable .item-total-pcs,
#purchaseItemsTable .item-line-total { font-weight:600; white-space:nowrap; }
.scan-ok { border-color: #28a745 !important; }
#selectAllCategoryBtn { height:44px; white-space:nowrap; }
#purchaseCategoryFilter { height:44px; }
</style>

<div class="purchase-item-box">
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
        <label class="mb-0"><i class="fas fa-barcode"></i> {{__('admin.Scan or Search Product')}}</label>
        <small class="text-muted" id="purchaseProductCount">{{ $products->count() }} {{__('admin.Products')}}</small>
    </div>

    <div class="purchase-filter-row">
        <div class="form-group">
            <label for="purchaseCategoryFilter">{{__('admin.Category')}}</label>
            <select id="purchaseCategoryFilter" class="form-control">
                <option value="">{{__('admin.All Categories')}}</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="flex:0 0 auto;">
            <label>&nbsp;</label>
            <button type="button" class="btn btn-outline-primary d-block" id="selectAllCategoryBtn">
                <i class="fas fa-check-double"></i> {{__('admin.Select All in Category')}}
            </button>
        </div>
    </div>

    <div class="purchase-search-wrap">
        <i class="fas fa-search purchase-search-icon"></i>
        <input type="text" id="productScanSearchInput" class="form-control"
            placeholder="{{__('admin.Scan barcode, SKU or type product name then press Enter')}}"
            autocomplete="off" autofocus>
        <div class="purchase-search-actions">
            <button type="button" class="btn btn-primary btn-add-item" id="addProductBtn">
                <i class="fas fa-plus"></i> {{__('admin.Add')}}
            </button>
        </div>
        <div id="purchaseSearchResults"></div>
    </div>
    <small class="text-muted d-block mt-2">{{__('admin.PCS products: pack unit / pcs. KG products: enter quantity in KG and cost per KG')}}</small>
</div>

<div class="table-responsive">
    <table class="table table-bordered" id="purchaseItemsTable">
        <thead class="thead-light">
            <tr>
                <th style="width:18%">{{__('admin.Product')}}</th>
                <th>{{__('admin.SKU')}}</th>
                <th style="width:100px">{{__('admin.Unit')}}</th>
                <th style="width:90px">{{__('admin.Pcs Per Unit')}}</th>
                <th style="width:90px">{{__('admin.Quantity')}}</th>
                <th style="width:120px">{{__('admin.Purchase Price')}}</th>
                <th style="width:100px">{{__('admin.Per Pc Cost')}}</th>
                <th style="width:100px">{{__('admin.Base Qty')}}</th>
                <th style="width:90px">{{__('admin.Line Total')}}</th>
                <th style="width:55px">{{__('admin.Action')}}</th>
            </tr>
        </thead>
        <tbody id="purchaseItemsBody"></tbody>
    </table>
</div>
<p id="purchaseEmptyHint" class="text-muted text-center py-3">{{ $emptyText }}</p>

<script>
window.purchaseProducts = @json($productJson);
window.purchaseQtyName = @json($qtyName);
window.purchaseCostName = @json($costName);
window.purchaseUnits = @json($unitJson);
</script>
