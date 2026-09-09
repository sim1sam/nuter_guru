@extends('admin.master_layout')
@section('title')
<title>{{__('Pos')}}</title>
@endsection
@section('style')
<link
    href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,800;0,900;1,700&family=Roboto:wght@300;400;500;700&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="{{ asset('backend/pos/assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('backend/pos/assets/css/respondive.css') }}">
<link rel="stylesheet" href="{{ asset('backend/pos/assets/css/pos-modern.css') }}">
<script>document.documentElement.classList.add('pos-fullscreen');</script>
<style>
    .pos-customer-bar { display: flex; gap: 10px; align-items: stretch; width: 100%; }
    .pos-customer-search-wrap { flex: 1; min-width: 0; position: relative; }
    .pos-customer-search-wrap .form-control {
        width: 100%;
        height: 56px;
        border-radius: 12px;
        padding: 0 16px;
        font-size: 15px;
        font-weight: 500;
    }
    .pos-customer-add-btn {
        flex-shrink: 0;
        width: 56px;
        height: 56px;
        min-width: 56px;
        padding: 0;
        border-radius: 12px;
        border: 1px solid var(--pos-primary, #8B7BA8);
        background: var(--pos-primary, #8B7BA8);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        font-weight: 300;
        line-height: 1;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s, transform 0.15s;
    }
    .pos-customer-add-btn:hover {
        background: var(--pos-primary-dark, #6f5f8c);
        border-color: var(--pos-primary-dark, #6f5f8c);
        color: #fff;
    }
    .pos-customer-results { position: absolute; left: 0; right: 0; top: calc(100% + 6px); background: #fff; color: #232532; z-index: 40; max-height: 280px; overflow: auto; border-radius: 12px; box-shadow: 0 10px 24px rgba(0,0,0,.12); display: none; }
    .pos-customer-results .item { padding: 12px 14px; cursor: pointer; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
    .pos-customer-results .item:last-child { border-bottom: 0; }
    .pos-customer-results .item small { display: block; color: #6b6b6b; font-size: 12px; }
    .pos-customer-results .no-result { cursor: default; color: #888; }
    .pos-address-box { margin-top: 4px; }
    .pos-address-card--warn { border-color: #f0d9a8; background: #fffaf0; }
    .pos-address-card__head { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 10px; }
    .pos-address-card__icon { width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .pos-address-card__meta { font-size: 12px; color: #6b6580; margin-top: 2px; }
    .pos-address-card__text { color: #4A4A5C; line-height: 1.5; font-size: 14px; }
</style>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content pos-main-content">
    <section class="section pos-wrapper-section">
        <div class="section-body">

            <section class="pos-fullscreen-shell">
                <div class="container-fluid">
                    <div class="row pos-layout-row">
                        <div class="col-lg-12 col-xl-7 pos-products-column product-bg">
                            <div class="pos-toolbar">
                                <div class="pos-toolbar__left">
                                    <a href="{{ route('admin.dashboard') }}" class="pos-back-link">
                                        <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 1L1 5M1 5L5 9M1 5L13 5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        {{__('admin.Back')}}
                                    </a>
                                    <h2 class="pos-toolbar__title">{{__('admin.Product Section')}}</h2>
                                </div>
                                <button type="button" class="pos-btn pos-btn--outline" data-toggle="modal" data-target="#exampleModalLong-2">
                                    {{__('Add Product') }}
                                </button>
                            </div>
                            <div class="pos-products-scroll">
                             <!-- Modal -->
                             <div class="modal fade" id="exampleModalLong-2"  role="dialog"
                             aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                             <div class="modal-dialog modal-dialog-two" role="document">
                                 <div class="modal-content">
                                     <div class="modal-header">
                                         <h5 class="modal-title" id="exampleModalLongTitle-1"> {{__('admin.Add New Product') }}</h5>
                                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                             <span aria-hidden="true">&times;</span>
                                         </button>
                                     </div>
                                     <div class="modal-body">
                                         <div class="modal-from">
                                             <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                                             @csrf
                                             <div class="row">
                                                 <div class="form-group col-12">
                                                     <label>{{__('admin.Thumbnail Image Preview')}}</label>
                                                     <div>
                                                         <img id="preview-img" class="admin-img" src="{{ asset('uploads/website-images/preview.png') }}" alt="">
                                                     </div>
                 
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Thumnail Image')}} <span class="text-danger">*</span></label>
                                                     <input type="file" class="form-control-file"  name="thumb_image" onchange="previewThumnailImage(event)" required>
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Short Name')}} <span class="text-danger">*</span></label>
                                                     <input type="text" id="short_name" class="form-control"  name="short_name" value="{{ old('short_name') }}" required>
                                                 </div>
                 
                                                 <div class="form-group col-12">
                                                     <label>{{__('admin.Name')}} <span class="text-danger">*</span></label>
                                                     <input type="text" id="name" class="form-control"  name="name" value="{{ old('name') }}" required>
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Slug')}} <span class="text-danger">*</span></label>
                                                     <input type="text" id="slug" class="form-control"  name="slug" value="{{ old('slug') }}">
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Category')}} <span class="text-danger">*</span></label>
                                                     <select name="category" class="form-control select2" id="category" required>
                                                         <option value="">{{__('admin.Select Category')}}</option>
                                                         @foreach ($categories as $category)
                                                             <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                         @endforeach
                                                     </select>
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Sub Category')}}</label>
                                                     <select name="sub_category" class="form-control select2" id="sub_category">
                                                         <option value="">{{__('admin.Select Sub Category')}}</option>
                                                     </select>
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Child Category')}}</label>
                                                     <select name="child_category" class="form-control select2" id="child_category">
                                                         <option value="">{{__('admin.Select Child Category')}}</option>
                                                     </select>
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Brand')}} </label>
                                                     <select name="brand" class="form-control select2" id="brand">
                                                         <option value="">{{__('admin.Select Brand')}}</option>
                                                         @foreach ($brands as $brand)
                                                             <option {{ old('brand') == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                         @endforeach
                                                     </select>
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.SKU')}} </label>
                                                    <input type="text" class="form-control" name="sku">
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('Price')}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="price" value="{{ old('price') }}" required>
                                                 </div>
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Offer Price')}}</label>
                                                    <input type="text" class="form-control" name="offer_price" value="{{ old('offer_price') }}">
                                                 </div>
                 
                 
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Stock Quantity')}} <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="quantity" value="{{ old('quantity') }}" required>
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Weight')}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="weight" value="{{ old('weight') }}" required>
                                                 </div>
                 
                                                 <div class="form-group col-6">
                                                     <label>{{__('admin.Short Description')}} <span class="text-danger">*</span></label>
                                                     <textarea name="short_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('short_description') }}</textarea>
                                                 </div>
                                                 
                                                <div class="form-group col-6">
                                                     <label>{{__('admin.Long Description')}} <span class="text-danger">*</span></label>
                                                     <textarea name="long_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('long_description') }}</textarea>
                                                 </div>

                                                 <div class="form-group col-12">
                                                     <label>{{__('admin.Highlight')}}</label>
                                                     <div>
                                                         <input type="checkbox"name="top_product" id="top_product"> <label for="top_product" class="mr-3" >{{__('admin.Top Product')}}</label>
                 
                                                         <input type="checkbox" name="new_arrival" id="new_arrival"> <label for="new_arrival" class="mr-3" >{{__('admin.New Arrival')}}</label>
                 
                                                         <input type="checkbox" name="best_product" id="best_product"> <label for="best_product" class="mr-3" >{{__('admin.Best Product')}}</label>
                 
                                                         <input type="checkbox" name="is_featured" id="is_featured"> <label for="is_featured" class="mr-3" >{{__('admin.Featured Product')}}</label>
                                                     </div>
                                                 </div>
                 
                                                 <div class="form-group col-12">
                                                     <label>{{__('admin.Status')}} <span class="text-danger">*</span></label>
                                                     <select name="status" class="form-control" required>
                                                         <option value="1">{{__('admin.Active')}}</option>
                                                         <option value="0">{{__('admin.Inactive')}}</option>
                                                     </select>
                                                 </div>
                 
                 
                 
                 
                                                 <div class="form-group col-12">
                                                     <label>{{__('admin.SEO Title')}}</label>
                                                    <input type="text" class="form-control" name="seo_title" value="{{ old('seo_title') }}">
                                                 </div>
                 
                                                 <div class="form-group col-12">
                                                     <label>{{__('admin.SEO Description')}}</label>
                                                     <textarea name="seo_description" id="" cols="30" rows="10" class="form-control text-area-5">{{ old('seo_description') }}</textarea>
                                                 </div>
                                             </div>
                                             <div class="row">
                                                 <div class="col-12">
                                                      <button type="submit" class="modal-from-btm-btn">{{__('admin.Save') }}</button>
                                                 </div>
                                             </div>
                                         </form>
                                                                
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                        
                            <div class="row row-p-30 pos-filters">
                                <div class="col-lg-12 col-p-0">
                                    <div class="product-categories">
                                        <div class="product-categories-search">
                                            <div class="product-categories-search-main">
                                                <form action="{{ route('admin.pos.product.search') }}" method="GET" id="searchForm">
                                                    <input type="text" name="query" class="form-control" id="exampleFormControlInput1" placeholder="Search products...">
                                                </form>
                                            </div>

                                            <div class="product-categories-main-df">
                                                <button type="button" class="product-categories-search-main-icon" id="searchButton">
                                                    <span>
                                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M18.5 15.7076L20.4217 17.6292C21.1928 18.4004 21.1928 19.6506 20.4217 20.4217C19.6506 21.1928 18.4004 21.1928 17.6293 20.4217L15.7076 18.5M1 9.5C1 4.80558 4.80558 1 9.5 1C14.1944 1 18 4.80558 18 9.5C18 14.1944 14.1944 18 9.5 18C4.80558 18 1 14.1944 1 9.5Z"
                                                                stroke="#232532" stroke-width="1.5" stroke-linecap="round" />
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="sub-categories-btn">
                                        <div class="sub-categories-btn-text">
                                            <h6>{{__('admin.Categories')}}</h6>
                                        </div>

                                        <div class="sub-categories-all-btn">
                                            @foreach ($categories as $index => $category)
                                            <a
                                                href="{{ route('admin.pos.category.index',$category->id) }}">{{$category->name}}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row row-p-30">
                                @foreach ($products as $index => $product)
                                <!-- Modal -->


                                <div class="col-lg-3 col-md-4 col-sm-6 col-p-10px">
                                    <div class="product-item pos-product-card">
                                        @if ($product->qty == 0)
                                            <span class="pos-stock-badge pos-stock-badge--out">0 {{ __('admin.stock') }}</span>
                                        @else
                                            <span class="pos-stock-badge">{{ $product->qty }}</span>
                                        @endif
                                        <div class="product-item-img pos-product-card__img">
                                            <img src="{{ asset($product->thumb_image) }}" alt="{{ $product->short_name }}">
                                        </div>
                                        <div class="product-item-text pos-product-card__body">
                                            <p class="pos-product-card__name">{{ $product->short_name }}</p>
                                            <div class="product-item-text-btm pos-product-card__price">
                                                @if ($product->offer_price == '')
                                                    <span>{{ $setting->currency_icon }}{{ $product->price }}</span>
                                                @else
                                                    <span><del>{{ $setting->currency_icon }}{{ $product->price }}</del></span>
                                                    <span>{{ $setting->currency_icon }}{{ $product->offer_price }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="pos-product-card__actions">
                                            <button type="button" class="pos-btn pos-btn--ghost" data-toggle="modal" data-target="#exampleModalLong{{ $product->id }}">
                                                {{ __('admin.Details') }}
                                            </button>
                                            @if ($product->qty > 0)
                                                @if ($product->isKg() && $product->weightVariants->count() > 0)
                                                    <button type="button" class="pos-btn pos-btn--primary" data-toggle="modal" data-target="#exampleModalLong{{ $product->id }}">
                                                        {{ __('admin.Select') }}
                                                    </button>
                                                @else
                                                    <a href="{{ route('admin.pos.add.product', $product->id) }}" class="pos-btn pos-btn--primary pos-add-product">{{ __('admin.Select') }}</a>
                                                @endif
                                            @else
                                                <span class="pos-btn pos-btn--disabled">{{ __('admin.Select') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="exampleModalLong{{$product->id}}"
                                    role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-three " role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">{{__('admin.Product Details')}}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body modal-body-one">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="place-order-img">
                                                            <img src="{{ asset($product->thumb_image) }}" alt="img">

                                                            {{-- <div class="place-order-img-overlay">
                                                                <div class="icon">
                                                                    <h5>-50%</h5>
                                                                </div>
                                                            </div> --}}
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 raindo-pd">
                                                        <div class="place-order-text">
                                                            <span>{{ $product->short_name }}</span>
                                                            <h2>{{ $product->name }}</h2>
                                                        </div>

                                                        <div class="place-order-reviews">
                                                            <div class="icon">
                                                                <span>
                                                                    <svg width="80" height="16" viewBox="0 0 80 16"
                                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M8 0L9.79611 5.52786H15.6085L10.9062 8.94427L12.7023 14.4721L8 11.0557L3.29772 14.4721L5.09383 8.94427L0.391548 5.52786H6.20389L8 0Z"
                                                                            fill="#FFA800" />
                                                                        <path
                                                                            d="M24 0L25.7961 5.52786H31.6085L26.9062 8.94427L28.7023 14.4721L24 11.0557L19.2977 14.4721L21.0938 8.94427L16.3915 5.52786H22.2039L24 0Z"
                                                                            fill="#FFA800" />
                                                                        <path
                                                                            d="M40 0L41.7961 5.52786H47.6085L42.9062 8.94427L44.7023 14.4721L40 11.0557L35.2977 14.4721L37.0938 8.94427L32.3915 5.52786H38.2039L40 0Z"
                                                                            fill="#FFA800" />
                                                                        <path
                                                                            d="M56 0L57.7961 5.52786H63.6085L58.9062 8.94427L60.7023 14.4721L56 11.0557L51.2977 14.4721L53.0938 8.94427L48.3915 5.52786H54.2039L56 0Z"
                                                                            fill="#FFA800" />
                                                                        <path
                                                                            d="M72 0L73.7961 5.52786H79.6085L74.9062 8.94427L76.7023 14.4721L72 11.0557L67.2977 14.4721L69.0938 8.94427L64.3915 5.52786H70.2039L72 0Z"
                                                                            fill="#FFA800" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                            {{-- <div class="text">
                                                                <p>6 Reviews</p>
                                                            </div> --}}
                                                        </div>

                                                        <div class="place-order-del">
                                                            @if ($product->offer_price)
                                                            <span>
                                                                <del>{{ $setting->currency_icon }}{{ $product->offer_price }}</del>
                                                            </span>
                                                            @endif
                                                            <span>{{ $setting->currency_icon }}{{ $product->price }}</span>
                                                        </div>

                                                        <div class="place-order-p">
                                                            <p>
                                                                {!!$product->short_description!!}
                                                            </p>
                                                        </div>

                                                        <div class="availabillity">
                                                            <h2>
                                                                {{__('admin.Availabillity :')}}
                                                                @if ($product->qty == 0)
                                                                <span style="color: red;">{{__('admin.Stock Out')}}</span>
                                                                @else
                                                                <span>{{$product->qty}} {{__('admin.Products Available')}}</span>
                                                                @endif


                                                            </h2>
                                                        </div>
                                                        <form action="{{ route('admin.pos.cart.order.detils',$product->id) }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="selected_values" id="selected_values">
                                                            <div class="pt-3">
                                                                <div class="row">
                                                                    @if ($product->isKg() && $product->weightVariants->count() > 0)
                                                                        <div class="col-md-12 mb-2">
                                                                            <label for="weight_variant_{{ $product->id }}">{{ __('admin.Weight') ?? 'Weight' }}</label>
                                                                            <select id="weight_variant_{{ $product->id }}" name="weight_variant_id" class="form-control" required>
                                                                                <option value="" disabled selected>{{ __('Select') }}</option>
                                                                                @foreach ($product->weightVariants as $wv)
                                                                                    <option value="{{ $wv->id }}">
                                                                                        {{ $wv->name }}
                                                                                        ({{ rtrim(rtrim(number_format((float) $wv->weight_in_kg, 3, '.', ''), '0'), '.') }} KG)
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    @endif
                                                                    @foreach ($product->activeVariants as $variant)
                                                                        <div class="col-md-6">
                                                                            <label for="size">{{ $variant->name }}</label>
                                                                            <select id="size" name="selectedValues[{{ $variant->id }}]" class="form-control variant-select">
                                                                                <option value="" disabled selected>{{ __('Select') }}</option>
                                                                                @if ($variant->variantItems)
                                                                                    @foreach ($variant->variantItems as $variantItem)
                                                                                        <option value="{{ $variantItem->id }}">{{ $variantItem->name }}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>


                                                            <div class="add-to-cart">
                                                                <div class="col-md-4 mb-3 mt-1">
                                                                    <div class="qty-container">
                                                                        <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                                                        <input type="number" name="quantity" class="input-qty" value="1"  readonly/>
                                                                        <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                                                    </div>
                                                                </div>


                                                                <div class="add-to-cart-item-modal">
                                                                    <!-- Button trigger modal -->

                                                                    <button type="submit" class="btn-delete">
                                                                        <span>
                                                                            <svg width="14" height="14" viewBox="0 0 14 14"
                                                                                fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <g clip-path="url(#clip0_1344_5144)">
                                                                                    <path
                                                                                        d="M8.25309 3.32575C8.25309 4.00929 8.25145 4.69283 8.25418 5.37583C8.25527 5.68424 8.31488 5.74439 8.62382 5.74439C9.96351 5.74603 11.3027 5.74275 12.6423 5.74603C13.2723 5.74767 13.7392 6.05663 13.9241 6.58104C14.2204 7.42098 13.6135 8.24232 12.6757 8.25052C11.5914 8.25982 10.507 8.25271 9.42271 8.25271C9.17665 8.25271 8.93058 8.25216 8.68452 8.25271C8.29082 8.2538 8.25363 8.29154 8.25363 8.69838C8.25309 10.0195 8.25637 11.3412 8.25199 12.6624C8.24981 13.2836 7.92555 13.7544 7.39842 13.9305C6.56399 14.2088 5.75799 13.6062 5.74814 12.6821C5.73776 11.7251 5.74596 10.7687 5.74541 9.81173C5.74541 9.41965 5.74705 9.02812 5.74486 8.63604C5.74322 8.30849 5.68964 8.2538 5.36155 8.25326C4.02186 8.25162 2.68272 8.25545 1.34304 8.25107C0.719125 8.24943 0.249414 7.93008 0.0706069 7.40348C-0.212641 6.57065 0.387757 5.75916 1.30968 5.74658C2.14794 5.73564 2.98620 5.74384 3.82446 5.74384C4.30730 5.74384 4.79013 5.74384 5.27351 5.74384C5.72135 5.74330 5.74541 5.71869 5.74541 5.25716C5.74541 3.95406 5.74268 2.65096 5.74650 1.34786C5.74814 0.720643 6.06201 0.253102 6.58750 0.0704598C7.40826 -0.213893 8.21754 0.370671 8.25199 1.27349C8.25254 1.29154 8.25254 1.31013 8.25254 1.32817C8.25309 1.99531 8.25309 2.66026 8.25309 3.32575Z"
                                                                                        fill="white" />
                                                                                </g>
                                                                            </svg>
                                                                        </span>
                                                                        {{__('admin.Add to Cart')}}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>

                                                        <div class="catagory">
                                                            <p>{{__('admin.Category')}} <span>: {{$product->category_name->name}}</span>
                                                            </p>
                                                            {{-- <p>{{__('admin.Tags :')}}  <span>{{$product->tags}}</span></p> --}}
                                                            <p>{{__('admin.SKU :')}} <span>{{$product->sku}}</span></p>
                                                        </div>

                                                        <div class="social-icon">
                                                            <div class="social-icon-item">
                                                                <div class="text">
                                                                    <p>{{__('admin.Share This') }}</p>
                                                                </div>
                                                                <div class="icon">
                                                                    <a href="#" target="_blank">
                                                                        <span>
                                                                            <svg width="10" height="16"
                                                                                viewBox="0 0 10 16" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M3 16V9H0V6H3V4C3 1.3 4.7 0 7.1 0C8.3 0 9.2 0.1 9.5 0.1V2.9H7.8C6.5 2.9 6.2 3.5 6.2 4.4V6H10L9 9H6.3V16H3Z"
                                                                                    fill="#3E75B2" />
                                                                            </svg>
                                                                        </span>
                                                                    </a>
                                                                    <a href="#" target="_blank">
                                                                        <span>
                                                                            <svg width="16" height="16"
                                                                                viewBox="0 0 16 16" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M8 0C3.6 0 0 3.6 0 8C0 11.4 2.1 14.3 5.1 15.4C5 14.8 5 13.8 5.1 13.1C5.2 12.5 6 9.1 6 9.1C6 9.1 5.8 8.7 5.8 8C5.8 6.9 6.5 6 7.3 6C8 6 8.3 6.5 8.3 7.1C8.3 7.8 7.9 8.8 7.6 9.8C7.4 10.6 8 11.2 8.8 11.2C10.2 11.2 11.3 9.7 11.3 7.5C11.3 5.6 9.9 4.2 8 4.2C5.7 4.2 4.4 5.9 4.4 7.7C4.4 8.4 4.7 9.1 5 9.5C5 9.7 5 9.8 5 9.9C4.9 10.2 4.8 10.7 4.8 10.8C4.8 10.9 4.7 11 4.5 10.9C3.5 10.4 2.9 9 2.9 7.8C2.9 5.3 4.7 3 8.2 3C11 3 13.1 5 13.1 7.6C13.1 10.4 11.4 12.6 8.9 12.6C8.1 12.6 7.3 12.2 7.1 11.7C7.1 11.7 6.7 13.2 6.6 13.6C6.4 14.3 5.9 15.2 5.6 15.7C6.4 15.9 7.2 16 8 16C12.4 16 16 12.4 16 8C16 3.6 12.4 0 8 0Z"
                                                                                    fill="#E12828" />
                                                                            </svg>
                                                                        </span>
                                                                    </a>
                                                                    <a href="#">
                                                                        <span class="pl">
                                                                            <svg width="18" height="14"
                                                                                viewBox="0 0 18 14" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M17.0722 1.60052C16.432 1.88505 15.7562 2.06289 15.0448 2.16959C15.7562 1.74278 16.3253 1.06701 16.5742 0.248969C15.8985 0.640206 15.1515 0.924742 14.3335 1.10258C13.6933 0.426804 12.7686 0 11.7727 0C9.85206 0 8.28711 1.56495 8.28711 3.48557C8.28711 3.7701 8.32268 4.01907 8.39382 4.26804C5.51289 4.12577 2.9165 2.73866 1.17371 0.604639C0.889175 1.13814 0.71134 1.70722 0.71134 2.34742C0.71134 3.5567 1.31598 4.62371 2.27629 5.26392C1.70722 5.22835 1.17371 5.08608 0.675773 4.83711V4.87268C0.675773 6.5799 1.88505 8.00258 3.48557 8.32268C3.20103 8.39382 2.88093 8.42938 2.56082 8.42938C2.34742 8.42938 2.09845 8.39382 1.88505 8.35825C2.34742 9.74536 3.62784 10.7768 5.15722 10.7768C3.94794 11.7015 2.45412 12.2706 0.818041 12.2706C0.533505 12.2706 0.248969 12.2706 0 12.2351C1.56495 13.2309 3.37887 13.8 5.37062 13.8C11.8082 13.8 15.3294 8.46495 15.3294 3.84124C15.3294 3.69897 15.3294 3.52113 15.3294 3.37887C16.0052 2.9165 16.6098 2.31186 17.0722 1.60052Z"
                                                                                    fill="#3FD1FF" />
                                                                            </svg>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                <div class="col-lg-12">
                                    <div class="pagination-btn">
                                        {{ $products->links() }}
                                    </div>
                                </div>

                            </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-xl-5 pos-cart-column col-lg-pl-30px">
                            <div class="row billing-main-box">
                                <div class="col-lg-12 product-padding pos-cart-inner">
                                    <div class="pos-cart-top">
                                        <div class="billing-section-taitel">
                                            <h3>{{__('admin.Billing Section') }}</h3>
                                            <span class="pos-cart-count" id="posCartCount">{{ $cart_products->count() }}</span>
                                        </div>

                                        <div class="billing-btn-main">
                                            <div class="pos-customer-bar">
                                                <div class="pos-customer-search-wrap">
                                                    <input type="text"
                                                        id="posCustomerSearch"
                                                        class="form-control"
                                                        autocomplete="off"
                                                        placeholder="{{ __('admin.Search customer by name, email or phone') }}"
                                                        value="{{ $selected_customer->name ?? '' }}">
                                                    <input type="hidden" id="posCustomerId" value="{{ $selected_customer->id ?? '' }}">
                                                    <div id="posCustomerResults" class="pos-customer-results"></div>
                                                </div>
                                                <button type="button"
                                                    class="pos-customer-add-btn"
                                                    data-toggle="modal"
                                                    data-target="#exampleModalLong-1"
                                                    title="{{ __('admin.Add Customer') }}"
                                                    aria-label="{{ __('admin.Add Customer') }}">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                <div class="delivery-information pos-cart-body">
                                    <div class="delivery-information-taitel">
                                        <h3>{{__('admin.Selected Product')}}</h3>
                                    </div>

                                    <div id="posCartPanel" class="pos-cart-scroll">
                                        @include('admin.pos.partials.cart_refresh', [
                                            'setting' => $setting,
                                            'cart_products' => $cart_products,
                                            'coupon' => $coupon ?? null,
                                            'couponValue' => $couponValue ?? '',
                                        ])
                                    </div>

                                    <div class="sub-total-btn pos-cart-actions">
                                        <div class="sub-total-btn-one">


                                            <button type="button" class="cancel-btn" data-toggle="modal"
                                                data-target="#exampleModalLong-3">
                                                {{__('admin.Cancel Order') }}
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModalLong-3"  role="dialog"
                                                aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">

                                                        </div>
                                                        <div class="modal-body modal-body-one">
                                                            <div class="modal-img text-center">
                                                                <img src="{{ asset('backend/pos/assets/images/clear-cart.png') }}"
                                                                    alt="img">
                                                            </div>

                                                            <div class="modal-img-text">
                                                                <h4>{{__('admin.Are you sure') }}</h4>
                                                                <p>{{__('admin.You want to remove all items from cart!!') }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">

                                                            <button type="button" class="no-btn yes-btn"
                                                                data-dismiss="modal">{{__('admin.No') }}</button>

                                                            <a class="no-btn pos-cart-clear"
                                                                href="{{ route('admin.pos.cart.clear.product') }}">
                                                                {{__('admin.Yes') }}
                                                            </a>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal -->


                                        </div>



                                        <div class="sub-total-btn-two">
                                            <button type="button" class="place-order" data-toggle="modal"
                                                data-target="#exampleModal-4">
                                                {{__('admin.Place Order') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


        </div>
    </section>
</div>

{{-- Payment / Place Order modal — outside cart so overlay covers full screen --}}
<div class="modal fade pos-payment-modal" id="exampleModal-4" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel-00" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered pos-payment-modal__dialog" role="document">
        <div class="modal-content pos-payment-modal__content">
            <div class="modal-header pos-payment-modal__header">
                <h5 class="modal-title" id="exampleModalLabel-00">{{ __('admin.Payment') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pos-payment-modal__body">
                <form action="{{ route('admin.pos.order.submit') }}" method="post" id="posPlaceOrderForm">
                    @csrf
                    <input type="hidden" name="sub_total" id="posOrderSubTotal" value="{{ $grandTotal ?? 0 }}">

                    @if(($grandTotal ?? 0) == 0)
                    <input type="hidden" name="cupon" id="posOrderCoupon" value="0">
                    <input type="hidden" name="tax" id="posOrderTax" value="0">
                    <input type="hidden" name="discount" id="posOrderDiscount" value="0">
                    @else
                    <input type="hidden" name="tax" id="posOrderTax" value="{{ $tax ?? 0 }}">
                    <input type="hidden" name="cupon" id="posOrderCoupon" value="{{ $couponValue ?? '' }}">
                    <input type="hidden" name="discount" id="posOrderDiscount" value="{{ $discount ?? 0 }}">
                    @endif

                    <div class="form-group">
                        <label for="posOrderCustomerSelect">{{ __('admin.Select Customer') }}</label>
                        <select name="customer_id" id="posOrderCustomerSelect" class="form-control select2" required>
                            <option value="">{{ __('admin.Select a Customer') }}</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ ($selected_customer->id ?? null) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}{{ $customer->phone ? ' ('.$customer->phone.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="posCustomerAddressBox" class="pos-address-box mb-3" style="display:none;">
                        <div id="posAddressCard" class="pos-address-card" style="display:none;">
                            <div class="pos-address-card__head">
                                <span class="pos-address-card__icon"><i class="fas fa-map-marker-alt"></i></span>
                                <div>
                                    <strong id="posAddressCardTitle">{{ __('admin.Delivery Address') }}</strong>
                                    <div id="posAddressCardMeta" class="pos-address-card__meta"></div>
                                </div>
                            </div>
                            <p id="posAddressCardText" class="pos-address-card__text mb-0"></p>
                            <small class="text-muted d-block mt-2">{{ __('admin.You can edit address for this order only.') }}</small>
                        </div>
                        <div id="posAddressMissing" class="pos-address-card pos-address-card--warn" style="display:none;">
                            <strong>{{ __('admin.No address found') }}.</strong>
                            <span class="d-block small mt-1">{{ __('admin.Please enter full address for this order.') }}</span>
                        </div>
                        <p class="text-muted small mb-2 mt-2">{{ __('admin.This address is saved only for this order') }}</p>
                        <div id="posAddressForm">
                            <div class="form-group mb-2">
                                <label>{{ __('admin.Country') }}</label>
                                <input type="text" class="form-control" value="Bangladesh" readonly>
                            </div>
                            <div class="form-group mb-2">
                                <label>{{ __('admin.Address') }} <span class="text-danger">*</span></label>
                                <textarea name="address_line" id="posAddressLine" class="form-control" rows="3" placeholder="{{ __('admin.House, road, area, landmark') }}" required></textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label class="d-block">{{ __('admin.Delivery Area') }} <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="delivery_area" id="posAreaInside" value="inside" checked>
                                    <label class="form-check-label" for="posAreaInside">{{ __('admin.Inside') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="delivery_area" id="posAreaOutside" value="outside">
                                    <label class="form-check-label" for="posAreaOutside">{{ __('admin.Outside') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="posOrderShippingSelect">{{ __('admin.Add Shipping') }}</label>
                        <select name="shipping_id" id="posOrderShippingSelect" class="form-control select2" required>
                            <option value="" disabled selected>{{ __('admin.Select a shipping rule') }}</option>
                            @foreach ($shippings as $shipping)
                                <option value="{{ $shipping->id }}" data-rate="{{ $shipping->shipping_fee }}">
                                    {{ $shipping->shipping_rule }} ({{ currency_icon() }}{{ $shipping->shipping_fee }}/KG)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">KG cart: first 1 KG full rate, then proportional (1.5 KG = 70+35)</small>
                    </div>

                    <div class="form-group">
                        <label for="posOrderPaymentSelect">{{ __('admin.Payment Method') }}</label>
                        <select name="payment_method" id="posOrderPaymentSelect" class="form-control" required>
                            <option value="" disabled selected>{{ __('Select Payment Method') }}</option>
                            <option value="Cash">{{ __('admin.Cash') }}</option>
                            <option value="Cash on Delivery">{{ __('admin.Cash on Delivery') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="posOrderStatusSelect">{{ __('admin.Order') }}</label>
                        <select name="order_status" id="posOrderStatusSelect" class="form-control" required>
                            <option value="" disabled>{{ __('admin.Select Order Status') }}</option>
                            <option value="0" selected>{{ __('admin.Pending') }}</option>
                            <option value="1">{{ __('admin.Processing') }}</option>
                            <option value="5">{{ __('admin.Shipment') }}</option>
                            <option value="2">{{ __('admin.Delivered') }}</option>
                            <option value="3">{{ __('admin.Return') }}</option>
                            <option value="4">{{ __('admin.Cancel') }}</option>
                        </select>
                    </div>

                    <div class="pos-payment-modal__footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('admin.Cancel') }}</button>
                        <button type="submit" class="btn btn-primary pos-payment-modal__submit">{{ __('admin.Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Add Customer modal — outside cart panel so overlay covers full screen --}}
<div class="modal fade pos-customer-modal" id="exampleModalLong-1" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLongTitle-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered pos-customer-modal__dialog" role="document">
        <div class="modal-content pos-customer-modal__content">
            <div class="modal-header pos-customer-modal__header">
                <h5 class="modal-title" id="exampleModalLongTitle-1">{{ __('admin.Add New Customer') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pos-customer-modal__body">
                <form action="{{ route('admin.pos.add.customer') }}" method="post" class="pos-customer-form">
                    @csrf
                    <div class="form-group">
                        <label for="posCustomerName">{{ __('admin.Full Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="posCustomerName" placeholder="{{ __('admin.Full Name') }}" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="posCustomerEmail">{{ __('admin.Email Address') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="posCustomerEmail" placeholder="info@example.com" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="posCustomerPhone">{{ __('admin.Phone Number') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone" id="posCustomerPhone" placeholder="{{ __('admin.Phone Number') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ __('admin.Country') }}</label>
                        <input type="text" class="form-control" value="Bangladesh" readonly>
                    </div>
                    <div class="form-group">
                        <label for="posCustomerAddress">{{ __('admin.Address') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="address" id="posCustomerAddress" rows="3" placeholder="{{ __('admin.House, road, area, landmark') }}" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="d-block mb-2">{{ __('admin.Delivery Area') }} <span class="text-danger">*</span></label>
                        <div class="pos-radio-group">
                            <label class="pos-radio-pill">
                                <input type="radio" name="delivery_area" value="inside" checked>
                                <span>{{ __('admin.Inside') }}</span>
                            </label>
                            <label class="pos-radio-pill">
                                <input type="radio" name="delivery_area" value="outside">
                                <span>{{ __('admin.Outside') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="d-block mb-2">{{ __('admin.Location') }}</label>
                        <div class="pos-radio-group">
                            <label class="pos-radio-pill">
                                <input type="radio" name="location" value="Home" checked>
                                <span>{{ __('admin.Home') }}</span>
                            </label>
                            <label class="pos-radio-pill">
                                <input type="radio" name="location" value="Office">
                                <span>{{ __('admin.Office') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="pos-customer-modal__footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{ __('admin.Cancel') }}</button>
                        <button type="submit" class="btn btn-primary pos-customer-modal__submit">{{ __('admin.Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('backend/pos/assets/js/custom.js')}}"></script>
<script>
    (function ($) {
        var searchTimer = null;
        var csrf = '{{ csrf_token() }}';
        var $search = $('#posCustomerSearch');
        var $results = $('#posCustomerResults');
        var $hidden = $('#posCustomerId');

        function setOrderCustomer(id) {
            var $select = $('#posOrderCustomerSelect');
            if ($select.length) {
                $select.val(String(id)).trigger('change');
            }
        }

        function posToast(message, type) {
            if (typeof toastr !== 'undefined') {
                if (type === 'error') toastr.error(message);
                else if (type === 'warning') toastr.warning(message);
                else toastr.success(message);
            }
        }

        function syncPosOrderTotals(totals) {
            if (!totals) return;
            $('#posOrderSubTotal').val(totals.grandTotal);
            $('#posOrderTax').val(totals.tax);
            $('#posOrderDiscount').val(totals.discount);
            $('#posOrderCoupon').val(totals.couponValue || '0');
        }

        function refreshPosCart(res) {
            if (res && res.html) {
                $('#posCartPanel').html(res.html);
                var count = $('#posCartItems .pos-cart-item').length;
                $('#posCartCount').text(count);
            }
            if (res && res.totals) {
                syncPosOrderTotals(res.totals);
            }
            if (res && res.message) {
                posToast(res.message, res.alert || (res.ok ? 'success' : 'error'));
            }
        }

        function posCartRequest(url) {
            return $.ajax({
                url: url,
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function (res) {
                refreshPosCart(res);
            }).fail(function (xhr) {
                var res = xhr.responseJSON || {};
                if (res.message) posToast(res.message, 'error');
            });
        }

        $(document).on('click', '.pos-add-product', function (e) {
            e.preventDefault();
            posCartRequest($(this).attr('href'));
        });

        $(document).on('click', '.pos-cart-action', function (e) {
            e.preventDefault();
            posCartRequest($(this).attr('href'));
        });

        $(document).on('click', '.pos-cart-clear', function (e) {
            e.preventDefault();
            posCartRequest($(this).attr('href'));
            $('#exampleModalLong-3').modal('hide');
        });

        $(document).on('submit', '#posPromoWrap form', function (e) {
            e.preventDefault();
            var coupon = $.trim($(this).find('[name=coupon]').val());
            if (!coupon) {
                posToast('{{ trans('admin_validation.Coupon Field is required') }}', 'error');
                return;
            }
            $.get('{{ route('admin.pos.apply.cupon') }}', { coupon: coupon }, function (res) {
                refreshPosCart(res);
            }, 'json').fail(function (xhr) {
                var res = xhr.responseJSON || {};
                if (res.message) posToast(res.message, 'error');
            });
        });

        function loadCustomerAddress(customerId) {
            var $box = $('#posCustomerAddressBox');
            if (!customerId) {
                $box.hide();
                return;
            }
            $box.show();
            $.getJSON('{{ url('admin/pos/customer') }}/' + customerId + '/address', function (res) {
                $('#posAddressForm').show();
                $('#posAddressLine').val('');
                $('#posAreaInside').prop('checked', true);
                $('#posAddressCard').hide();
                $('#posAddressMissing').hide();

                if (res.has_address && res.address) {
                    var areaLabel = (res.address.delivery_area === 'outside')
                        ? '{{ __('admin.Outside') }}'
                        : '{{ __('admin.Inside') }}';
                    var title = res.address.is_default
                        ? '{{ __('admin.Default address loaded') }}'
                        : '{{ __('admin.Address available') }}';
                    var meta = [];
                    if (res.address.phone) meta.push(res.address.phone);
                    meta.push('Bangladesh · ' + areaLabel);
                    $('#posAddressCardTitle').text(title);
                    $('#posAddressCardMeta').text(meta.join(' · '));
                    $('#posAddressCardText').text(res.address.address || '');
                    $('#posAddressCard').show();
                    $('#posAddressLine').val(res.address.address || '');
                    if (res.address.delivery_area === 'outside') {
                        $('#posAreaOutside').prop('checked', true);
                    } else {
                        $('#posAreaInside').prop('checked', true);
                    }
                } else {
                    $('#posAddressMissing').show();
                }
            });
        }

        $(document).on('change', '#posOrderCustomerSelect', function () {
            loadCustomerAddress($(this).val());
        });

        $('#exampleModalLong-1').on('show.bs.modal', function () {
            $(this).appendTo('body');
        });

        $('#exampleModal-4').on('show.bs.modal', function () {
            $(this).appendTo('body');
            var $modal = $(this);
            $modal.find('.select2').each(function () {
                var $el = $(this);
                if ($el.hasClass('select2-hidden-accessible')) {
                    $el.select2('destroy');
                }
                $el.select2({
                    width: '100%',
                    dropdownParent: $modal
                });
            });
        });

        $('#exampleModal-4').on('shown.bs.modal', function () {
            loadCustomerAddress($('#posOrderCustomerSelect').val());
        });

        function pickCustomer(customer) {
            $hidden.val(customer.id);
            $search.val(customer.name);
            $results.hide().empty();
            setOrderCustomer(customer.id);
            $.post('{{ route('admin.pos.customer.select') }}', {
                _token: csrf,
                customer_id: customer.id
            });
        }

        $search.on('input', function () {
            var q = $.trim(this.value);
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                $.getJSON('{{ route('admin.pos.customer.search') }}', { q: q }, function (list) {
                    if (!list.length) {
                        $results.html('<div class="item no-result">{{ __('admin.No customers found') }}</div>').show();
                        return;
                    }
                    var html = '';
                    list.forEach(function (customer) {
                        var meta = [customer.phone, customer.email].filter(Boolean).join(' · ');
                        html += '<div class="item" data-id="' + customer.id + '"><strong>' + (customer.name || '') + '</strong>' +
                            (meta ? '<small>' + meta + '</small>' : '') + '</div>';
                    });
                    $results.html(html).show();
                    $results.find('.item[data-id]').on('click', function () {
                        var id = Number($(this).data('id'));
                        var found = list.find(function (item) { return Number(item.id) === id; });
                        if (found) {
                            pickCustomer(found);
                        }
                    });
                });
            }, 250);
        });

        $search.on('focus', function () {
            if ($.trim(this.value).length) {
                $(this).trigger('input');
            }
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.pos-customer-search-wrap').length) {
                $results.hide();
            }
        });

        $(document).on('change', '.pos-qty-input', function () {
            var $input = $(this);
            var cartId = $input.data('cart-id');
            var qty = parseInt($input.val(), 10);
            var original = parseInt($input.attr('value'), 10) || 1;
            if (!qty || qty < 1) {
                $input.val(original);
                return;
            }
            if (qty === original) {
                return;
            }
            var payload = { _token: csrf, _method: 'PUT', qty_update: {} };
            payload.qty_update[cartId] = qty;
            $.ajax({
                url: '{{ route('admin.pos.update.cart.order') }}',
                method: 'POST',
                data: payload,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).done(function (res) {
                refreshPosCart(res);
                $input.attr('value', qty);
            }).fail(function (xhr) {
                var message = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : '{{ trans('admin_validation.This Product Are Out of Stock') }}';
                if (typeof toastr !== 'undefined') {
                    toastr.error(message);
                } else {
                    alert(message);
                }
                $input.val(original);
            });
        });
    })(jQuery);
</script>
<script>
    const searchForm = document.getElementById('searchForm');
    const searchButton = document.getElementById('searchButton');

    searchButton.addEventListener('click', function() {
        searchForm.submit();
    });
</script>

<script>
    $(document).ready(function () {
        $('form').submit(function () {
            var selectedValues = {};
            $('.variant-select').each(function () {
                var variantName = $(this).data('variant');
                var selectedValue = $(this).val();
                selectedValues[variantName] = selectedValue;
            });
            $('#selected_values').val(JSON.stringify(selectedValues));
        });
    });
</script>

<script>
    $(document).ready(function () {
        var buttonPlus = $(".qty-btn-plus");
        var buttonMinus = $(".qty-btn-minus");

        buttonPlus.click(function () {
            var $n = $(this).parent(".qty-container").find(".input-qty");
            $n.val(Number($n.val()) + 1);
        });

        buttonMinus.click(function () {
            var $n = $(this).parent(".qty-container").find(".input-qty");
            var amount = Number($n.val());
            if (amount > 0) {
                $n.val(amount - 1);
            }
        });

        // Ensure the quantity value is initially set
        var $initialQty = $(".input-qty");
        $initialQty.val(Number($initialQty.val()));
    });
</script>
<script>
    (function($) {
        "use strict";
        var specification = true;
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
                var html = $("#hidden-specification-box").html();
                $("#specification-box").append(html);
            })

            $(document).on('click', '.deleteSpeceficationBtn', function () {
                $(this).closest('.delete-specification-row').remove();
            });


            $("#manageSpecificationBox").on("click",function(){
                if(specification){
                    specification = false;
                    $("#specification-box").addClass('d-none');
                }else{
                    specification = true;
                    $("#specification-box").removeClass('d-none');
                }


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

</script>


@endsection
