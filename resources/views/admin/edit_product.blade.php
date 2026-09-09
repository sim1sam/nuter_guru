@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Products')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{__('admin.Edit Product')}}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{__('admin.Edit Product')}}</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.product.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('admin.Products')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.product.update',$product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumbnail Image Preview')}}</label>
                                    <div>
                                        <img id="preview-img" class="admin-img" src="{{ asset($product->thumb_image) }}" alt="">
                                    </div>

                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Thumnail Image')}} <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file"  name="thumb_image" onchange="previewThumnailImage(event)">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>{{__('admin.Short Name')}} (EN) <span class="text-danger">*</span></label>
                                    <input type="text" id="short_name" class="form-control"  name="short_name" value="{{ old('short_name', $product->short_name) }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>{{__('admin.Short Name')}} (বাংলা)</label>
                                    <input type="text" id="short_name_bn" class="form-control"  name="short_name_bn" value="{{ old('short_name_bn', $product->short_name_bn) }}" placeholder="বাংলা সংক্ষিপ্ত নাম">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>{{__('admin.Name')}} (EN) <span class="text-danger">*</span></label>
                                    <input type="text" id="name" class="form-control"  name="name" value="{{ old('name', $product->name) }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>{{__('admin.Name')}} (বাংলা)</label>
                                    <input type="text" id="name_bn" class="form-control"  name="name_bn" value="{{ old('name_bn', $product->name_bn) }}" placeholder="বাংলা পণ্যের নাম">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                    <input type="text" id="slug" class="form-control"  name="slug" value="{{ $product->slug }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
                                    <select name="category" class="form-control select2" id="category">
                                        <option value="">{{__('admin.Select Category')}}</option>
                                        @foreach ($categories as $category)
                                            <option {{ $product->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Sub Category')}}</label>
                                    <select name="sub_category" class="form-control select2" id="sub_category">
                                        <option value="">{{__('admin.Select Sub Category')}}</option>
                                        @if ($product->category_id != 0)
                                            @foreach ($subCategories as $subCategory)
                                            <option {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }} value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Child Category')}}</label>
                                    <select name="child_category" class="form-control select2" id="child_category">
                                        <option value="">{{__('admin.Select Child Category')}}</option>
                                        @if ($product->sub_category_id != 0)
                                            @foreach ($childCategories as $childCategory)
                                            <option {{ $product->child_category_id == $childCategory->id ? 'selected' : '' }} value="{{ $childCategory->id }}">{{ $childCategory->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Brand')}}</label>
                                    <select name="brand" class="form-control select2" id="brand">
                                        <option value="">{{__('admin.Select Brand')}}</option>
                                        @foreach ($brands as $brand)
                                            <option {{ $product->brand_id == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SKU')}} </label>
                                   <input type="text" class="form-control" name="sku" value="{{ $product->sku }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Barcode')}} </label>
                                   <input type="text" class="form-control" name="barcode" value="{{ $product->barcode }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Low Stock Threshold')}} </label>
                                   <input type="number" class="form-control" name="low_stock_threshold" value="{{ $product->low_stock_threshold ?? 5 }}" min="0">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Product Unit Type')}} <span class="text-danger">*</span></label>
                                    <div class="d-flex" style="gap:18px;">
                                        <label class="mb-0"><input type="radio" name="unit_type" value="pcs" class="product-unit-type" {{ old('unit_type', $product->unit_type ?? 'pcs') === 'pcs' ? 'checked' : '' }}> PCS</label>
                                        <label class="mb-0"><input type="radio" name="unit_type" value="kg" class="product-unit-type" {{ old('unit_type', $product->unit_type ?? 'pcs') === 'kg' ? 'checked' : '' }}> KG</label>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{__('admin.Current stock')}}: <strong>{{ format_stock_qty($product->qty, $product) }}</strong>
                                        · <a href="{{ route('admin.inventory.stock-in') }}?product_id={{ $product->id }}">{{__('admin.Stock In')}}</a>
                                        · <a href="{{ route('admin.stock-history', $product->id) }}">{{__('admin.Stock History')}}</a>
                                    </small>
                                </div>

                                <div class="form-group col-12">
                                    <label><span class="add-stock-label">{{__('admin.Add Stock')}} ({{ product_unit_label($product) }})</span></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="number" step="0.001" min="0" class="form-control" name="add_stock_qty" value="{{ old('add_stock_qty') }}" placeholder="0">
                                        </div>
                                        <div class="col-md-6">
                                            <select name="add_stock_warehouse_id" class="form-control">
                                                @foreach(($warehouses ?? collect()) as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ $warehouse->is_default ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{__('admin.Leave empty to keep current stock. PO stock updates only after Receive Stock')}}</small>
                                </div>

                                <div class="form-group col-12 pcs-only-fields">
                                    <label>{{__('admin.Pcs Per Pack Unit')}}</label>
                                   <input type="number" class="form-control" name="pcs_per_box" value="{{ old('pcs_per_box', $product->pcs_per_box ?? 1) }}" min="1">
                                   <small class="text-muted">{{__('admin.How many Pcs in 1 pack unit. Stock and sales stay in Pcs')}}</small>
                                </div>

                                <div class="form-group col-12 pcs-only-fields">
                                    <label>{{__('admin.Default Purchase Unit')}}</label>
                                    <select name="purchase_unit" class="form-control">
                                        @foreach(($units ?? collect()) as $unit)
                                        <option value="{{ $unit->code }}" {{ old('purchase_unit', $product->purchase_unit ?? 'pc') === $unit->code ? 'selected' : '' }}>{{ $unit->name }}{{ $unit->is_base ? ' ('.__('admin.Stock unit').')' : '' }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted"><a href="{{ route('admin.unit.index') }}" target="_blank">{{__('admin.Create more units')}}</a></small>
                                </div>

                                <div class="form-group col-12">
                                    <label><span class="sell-price-label">{{__('admin.Price')}}</span> <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control" name="price" id="sellPriceInput" value="{{ $product->price }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{ __('admin.Offer Price')}} </label>
                                   <input type="text" class="form-control" name="offer_price" id="offerPriceInput" value="{{ $product->offer_price }}">
                                </div>

                                <div class="form-group col-12 kg-only-fields" style="display:none;">
                                    <label>{{__('admin.Selling Price Mode')}}</label>
                                    <select name="selling_price_mode" id="sellingPriceMode" class="form-control">
                                        <option value="automatic" {{ old('selling_price_mode', $product->selling_price_mode ?? 'automatic') === 'automatic' ? 'selected' : '' }}>{{__('admin.Automatic per KG')}}</option>
                                        <option value="custom" {{ old('selling_price_mode', $product->selling_price_mode ?? 'automatic') === 'custom' ? 'selected' : '' }}>{{__('admin.Custom variant price')}}</option>
                                    </select>
                                </div>

                                <div class="form-group col-12 kg-only-fields" style="display:none;">
                                    <div class="alert alert-info py-2 mb-0">
                                        <small>
                                            Shipping is weight-wise: first 1 KG full rate, then proportional
                                            (e.g. 1.5 KG Inside = ৳70+৳35 = ৳105). Configure under
                                            <a href="{{ route('admin.shipping.index') }}" target="_blank">Location → Shipping Rule</a>.
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group col-12 kg-only-fields" style="display:none;">
                                    <label class="d-flex justify-content-between align-items-center">
                                        <span>{{__('admin.Weight Variants')}}</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openQuickWeightVariantModal()">
                                            <i class="fa fa-plus"></i> {{__('admin.Add New')}}
                                        </button>
                                    </label>
                                    <small class="text-muted d-block mb-2">Select packs for this product. Need 100g / 2kg / 5kg? Click Add New.</small>
                                    <div class="row" id="weightVariantCards">
                                        @foreach(($weightVariants ?? collect()) as $wv)
                                        @php
                                            $isChecked = in_array($wv->id, old('weight_variant_ids', $selectedWeightVariantIds ?? []));
                                            $customPrice = old('weight_variant_prices.'.$wv->id, $customWeightPrices[$wv->id] ?? '');
                                        @endphp
                                        <div class="col-md-4 mb-2">
                                            <label class="border rounded p-2 d-block mb-0 weight-variant-card">
                                                <input type="checkbox" name="weight_variant_ids[]" value="{{ $wv->id }}" class="weight-variant-check" data-kg="{{ $wv->weight_in_kg }}" data-name="{{ $wv->name }}" {{ $isChecked ? 'checked' : '' }}>
                                                <strong>{{ $wv->name }}</strong>
                                                <div class="small text-muted">{{ number_format($wv->weight_in_kg, 3) }} KG</div>
                                                <div class="custom-price-wrap mt-1" style="display:none;">
                                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm weight-custom-price" name="weight_variant_prices[{{ $wv->id }}]" value="{{ $customPrice }}" placeholder="{{__('admin.Custom selling price')}}">
                                                </div>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div id="weightVariantPreview" class="mt-2 small text-muted"></div>
                                    <a href="{{ route('admin.weight-variant.index') }}" target="_blank" class="small">Manage all weight variants</a>
                                </div>

                                <div class="form-group col-12">
                                    <label><span class="cost-price-label">{{ __('admin.Purchase Price (Per Pc)') }}</span></label>
                                   <input type="number" step="0.0001" class="form-control" name="cost_price" id="costPriceInput" value="{{ old('cost_price', $product->cost_price > 0 ? $product->cost_price : '') }}" min="0">
                                    <small class="text-muted">{{ __('admin.Purchase price is set on PO and saved to product when stock is received') }}</small>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Default Supplier')}}</label>
                                    <select name="default_supplier_id" class="form-control select2">
                                        <option value="">{{__('admin.Select Supplier')}}</option>
                                        @foreach(($suppliers ?? collect()) as $supplier)
                                        <option value="{{ $supplier->id }}" {{ (string) old('default_supplier_id', $product->default_supplier_id) === (string) $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    @if(($suppliers ?? collect())->isEmpty())
                                        <small class="text-danger d-block mt-1">
                                            No suppliers yet.
                                            <a href="{{ route('admin.supplier.index') }}" target="_blank">Create supplier</a>
                                            (Purchase → Suppliers), then refresh this page.
                                        </small>
                                    @endif
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Weight')}}</label>
                                   <input type="text" class="form-control" name="weight" value="{{ $product->weight }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Tag')}} <span class="text-danger">*</span></label>
                                   <input type="text" class="form-control tags" name="tags" value="{{ $product->tags }}">
                                </div>



                                <div class="form-group col-12">
                                    <label>{{__('admin.Short Description') }} <span class="text-danger">*</span></label>
                                    <textarea name="short_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ $product->short_description }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Long Description')}} <span class="text-danger">*</span></label>
                                    <textarea name="long_description" id="" cols="30" rows="10" class="summernote">{{ $product->long_description }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Highlight')}}</label>
                                    <div>

                                        <input {{ $product->is_top == 1 ? 'checked' : '' }} type="checkbox"name="top_product" id="top_product"> <label for="top_product" class="mr-3" >{{__('admin.Top Product')}}</label>

                                        <input {{ $product->new_product == 1 ? 'checked' : '' }}  type="checkbox" name="new_arrival" id="new_arrival"> <label for="new_arrival" class="mr-3" >{{__('admin.New Arrival')}}</label>

                                        <input {{ $product->is_best == 1 ? 'checked' : '' }}  type="checkbox" name="best_product" id="best_product"> <label for="best_product" class="mr-3" >{{__('admin.Best Product')}}</label>

                                        <input {{ $product->is_featured == 1 ? 'checked' : '' }}  type="checkbox" name="is_featured" id="is_featured"> <label for="is_featured" class="mr-3" >{{__('admin.Featured Product')}}</label>
                                    </div>
                                </div>

                                @if ($product->vendor_id != 0)
                                    <div class="form-group col-12">
                                        <label>{{__('admin.Product Request from seller')}} <span class="text-danger">*</span></label>
                                        <select name="approve_by_admin" class="form-control">
                                            <option {{ $product->approve_by_admin == 1 ? 'selected' : '' }} value="1">{{__('admin.Approved')}}</option>
                                            <option {{ $product->approve_by_admin == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group col-12">
                                    <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control">
                                        <option {{ $product->status == 1 ? 'selected' : '' }} value="1">{{__('admin.Active')}}</option>
                                        <option {{ $product->status == 0 ? 'selected' : '' }} value="0">{{__('admin.Inactive')}}</option>
                                    </select>
                                </div>


                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Title')}}</label>
                                   <input type="text" class="form-control" name="seo_title" value="{{ $product->seo_title }}">
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.SEO Description')}}</label>
                                    <textarea name="seo_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ $product->seo_description }}</textarea>
                                </div>

                                <div class="form-group col-12">
                                    <label>{{__('admin.Specifications')}}</label>
                                    <div>
                                        <input name="is_specification" id="status_toggle" type="checkbox" {{ $product->is_specification == 1 ? 'checked' : '' }} data-toggle="toggle" data-on="Enable" data-off="Disabled" data-onstyle="success" data-offstyle="danger">
                                    </div>
                                </div>
                                <div class="form-group col-12 {{ $product->is_specification == 1 ? '' : 'd-none' }}" id="specification-box">
                                        @if ($productSpecifications->count() != 0)
                                            @foreach ($productSpecifications as $productSpecification)
                                                <div class="row mt-2" id="existSpecificationBox-{{ $productSpecification->id }}">
                                                    <div class="col-md-5">
                                                        <label>{{__('admin.Key')}} <span class="text-danger">*</span></label>
                                                        <select name="keys[]" class="form-control">
                                                            @foreach ($specificationKeys as $specificationKey)
                                                                <option {{ $specificationKey->id == $productSpecification->product_specification_key_id ? 'selected' : '' }} value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label>{{__('admin.Specification')}} <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="specifications[]" value="{{ $productSpecification->specification }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger plus_btn removeExistSpecificationRow"  data-specificationiId="{{ $productSpecification->id }}"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label>{{__('admin.Key')}} <span class="text-danger">*</span></label>
                                                <select name="keys[]" class="form-control">
                                                    @foreach ($specificationKeys as $specificationKey)
                                                        <option value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label>{{__('admin.Specification')}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="specifications[]">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-success plus_btn" id="addNewSpecificationRow"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>

                                    </div>

                                <div id="hidden-specification-box" class="d-none">
                                    <div class="delete-specification-row">
                                        <div class="row mt-2">
                                            <div class="col-md-5">
                                                <label>{{__('admin.Key')}} <span class="text-danger">*</span></label>
                                                <select name="keys[]" class="form-control">
                                                    @foreach ($specificationKeys as $specificationKey)
                                                        <option value="{{ $specificationKey->id }}">{{ $specificationKey->key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <label>{{__('admin.Specification')}} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="specifications[]">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger plus_btn deleteSpeceficationBtn"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>


<script>
    (function($) {
        "use strict";
        var specification = {{ $product->is_specification == 1 ? 'true' : 'false' }};

        function syncSpecificationState(enabled) {
            specification = !!enabled;
            if (specification) {
                $("#specification-box").removeClass('d-none');
                $("#specification-box").find('input, select, button').prop('disabled', false);
            } else {
                $("#specification-box").addClass('d-none');
                $("#specification-box").find('input, select, button').prop('disabled', true);
            }
        }

        $(document).ready(function () {
            $("#name").on("focusout",function(e){
                $("#slug").val(convertToSlug($(this).val()));
            })

            $("#category").on("change",function(){
                var categoryId = $("#category").val();
                if(categoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/admin/subcategory-by-category/')}}"+"/"+categoryId,
                        success:function(response){
                            $("#sub_category").html(response.subCategories);
                            var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                            $("#child_category").html(response);

                        },
                        error:function(err){
                            console.log(err);

                        }
                    })
                }else{
                    var response= "<option value=''>{{__('admin.Select Sub Category')}}</option>";
                    $("#sub_category").html(response);
                    var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                    $("#child_category").html(response);
                }


            })

            $("#sub_category").on("change",function(){
                var SubCategoryId = $("#sub_category").val();
                if(SubCategoryId){
                    $.ajax({
                        type:"get",
                        url:"{{url('/admin/childcategory-by-subcategory/')}}"+"/"+SubCategoryId,
                        success:function(response){
                            $("#child_category").html(response.childCategories);
                        },
                        error:function(err){
                            console.log(err);

                        }
                    })
                }else{
                    var response= "<option value=''>{{__('admin.Select Child Category')}}</option>";
                    $("#child_category").html(response);
                }

            })

            $("#is_return").on('change',function(){
                var returnId = $("#is_return").val();
                if(returnId == 1){
                    $("#policy_box").removeClass('d-none');
                }else{
                    $("#policy_box").addClass('d-none');
                }

            })

            $("#addNewSpecificationRow").on('click',function(){
                if (!specification) {
                    return;
                }
                var html = $("#hidden-specification-box").html();
                $("#specification-box").append(html);
            })

            $(document).on('click', '.deleteSpeceficationBtn', function () {
                $(this).closest('.delete-specification-row').remove();
            });

            $("#status_toggle").on('change', function () {
                syncSpecificationState($(this).is(':checked'));
            });

            syncSpecificationState($("#status_toggle").is(':checked'));

            $(".removeExistSpecificationRow").on("click",function(){
                var isDemo = "{{ env('APP_MODE') }}"
                if(isDemo == 'DEMO'){
                    toastr.error('This Is Demo Version. You Can Not Change Anything');
                    return;
                }
                var specificationId = $(this).attr("data-specificationiId");
                $.ajax({
                    type:"put",
                    data: { _token : '{{ csrf_token() }}' },
                    url:"{{url('/admin/removed-product-exist-specification/')}}"+"/"+specificationId,
                    success:function(response){
                        toastr.success(response)
                        $("#existSpecificationBox-"+specificationId).remove();
                    },
                    error:function(err){
                        console.log(err);

                    }
                })
            })

        });
    })(jQuery);

    function convertToSlug(Text){
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-');
    }

    function previewThumnailImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview-img');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    };

    (function ($) {
        function isKg() {
            return $('input[name="unit_type"]:checked').val() === 'kg';
        }
        function toggleUnitTypeFields() {
            if (isKg()) {
                $('.pcs-only-fields').hide();
                $('.kg-only-fields').show();
                $('.sell-price-label').text('{{ __('admin.Selling Price') }} / KG');
                $('.cost-price-label').text('{{ __('admin.Purchase Cost') }} / KG');
                $('.add-stock-label').text('{{ __('admin.Add Stock') }} (KG)');
            } else {
                $('.pcs-only-fields').show();
                $('.kg-only-fields').hide();
                $('.sell-price-label').text('{{ __('admin.Price') }}');
                $('.cost-price-label').text('{{ __('admin.Purchase Price (Per Pc)') }}');
                $('.add-stock-label').text('{{ __('admin.Add Stock') }} (PCS)');
            }
            toggleCustomPriceInputs();
            renderWeightPreview();
        }
        function toggleCustomPriceInputs() {
            $('.custom-price-wrap').toggle($('#sellingPriceMode').val() === 'custom' && isKg());
        }
        function renderWeightPreview() {
            if (!isKg()) {
                $('#weightVariantPreview').html('');
                return;
            }
            var cost = parseFloat($('#costPriceInput').val()) || 0;
            var sell = parseFloat($('#offerPriceInput').val());
            if (isNaN(sell) || sell <= 0) sell = parseFloat($('#sellPriceInput').val()) || 0;
            var mode = $('#sellingPriceMode').val();
            var html = '';
            $('.weight-variant-check:checked').each(function () {
                var kg = parseFloat($(this).data('kg')) || 0;
                var name = $(this).data('name');
                var purchase = (cost * kg).toFixed(2);
                var selling = mode === 'custom'
                    ? (parseFloat($(this).closest('label').find('.weight-custom-price').val()) || (sell * kg)).toFixed(2)
                    : (sell * kg).toFixed(2);
                html += '<div><strong>' + name + '</strong> — Purchase: ৳' + purchase + ' | Selling: ৳' + selling + '</div>';
            });
            $('#weightVariantPreview').html(html);
        }
        $('input[name="unit_type"]').on('change', toggleUnitTypeFields);
        $('#sellingPriceMode').on('change', function () { toggleCustomPriceInputs(); renderWeightPreview(); });
        $('#costPriceInput, #sellPriceInput, #offerPriceInput').on('input change', renderWeightPreview);
        $(document).on('change input', '.weight-variant-check, .weight-custom-price', renderWeightPreview);
        toggleUnitTypeFields();
    })(jQuery);

</script>

@include('admin.partials.quick_weight_variant_modal')

@endsection
