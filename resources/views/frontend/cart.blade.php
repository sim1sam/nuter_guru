@extends('frontend.layouts.app')

@section('title', __('Shopping Cart'))

@section('content')
<div class="cart-page">
    <div class="container">
        <div class="cart-page-header">
            <h1 class="cart-page-title">{{ __('Shopping Cart') }}</h1>
            <span class="cart-page-count">
                <i class="fas fa-shopping-bag"></i>
                <span id="cart-count">0</span> {{ __('items') }}
            </span>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="cart-panel">
                    <div class="cart-panel__head">
                        <h2>{{ __('Your Items') }}</h2>
                    </div>

                    <div id="cart-items" class="cart-items-list"></div>

                    <div id="empty-cart" class="cart-empty" style="display: none;">
                        <div class="cart-empty__icon"><i class="fas fa-shopping-bag"></i></div>
                        <h3>{{ __('Your cart is empty') }}</h3>
                        <p>{{ __('Add products you love and they will show up here.') }}</p>
                        <a href="{{ route('products') }}" class="cart-btn cart-btn--checkout">
                            <i class="fas fa-store"></i> {{ __('Continue Shopping') }}
                        </a>
                    </div>

                    <div id="login-prompt" class="cart-login-prompt" style="display: none;">
                        <div class="cart-login-prompt__icon"><i class="fas fa-user-lock"></i></div>
                        <h3>{{ __('Please log in to view your cart') }}</h3>
                        <p>{{ __('Sign in to access saved cart items and checkout faster.') }}</p>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('login') }}" class="cart-btn cart-btn--checkout">{{ __('Login') }}</a>
                            <a href="{{ route('register') }}" class="cart-btn cart-btn--secondary">{{ __('Register') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="cart-summary-card">
                    <h2>{{ __('Order Summary') }}</h2>

                    <div class="cart-summary-row">
                        <span>{{ __('Subtotal') }}</span>
                        <span id="subtotal">{{ currency_icon() }}0.00</span>
                    </div>

                    <div class="cart-summary-total">
                        <span>{{ __('Total') }}</span>
                        <span id="total">{{ currency_icon() }}0.00</span>
                    </div>

                    <div class="cart-summary-actions">
                        <button class="cart-btn cart-btn--checkout" id="checkout-btn" disabled>
                            <i class="fas fa-lock"></i> {{ __('Proceed to Checkout') }}
                        </button>
                        <a href="{{ route('products') }}" class="cart-btn cart-btn--secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('Continue Shopping') }}
                        </a>
                    </div>
                </div>

                <div class="cart-recommend-card">
                    <h3>{{ __('You might also like') }}</h3>
                    <div id="recommended-products" class="cart-recommend-list">
                        <p class="cart-recommend-empty mb-0">{{ __('Loading suggestions...') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
class ShoppingCart {
    constructor() {
        this.cart = [];
        this.currencyIcon = @json(currency_icon());
        this.defaultProductImage = @json(asset('frontend/images/default-product.svg'));
        this.init();
    }

    formatMoney(amount) {
        return `${this.currencyIcon}${parseFloat(amount).toFixed(2)}`;
    }

    init() {
        this.loadCartItems();
        this.loadRecommendedProducts();

        document.getElementById('checkout-btn').addEventListener('click', () => {
            this.proceedToCheckout();
        });
    }

    async loadCartItems() {
        try {
            const response = await fetch('/cart/items', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                this.cart = data.cart_items;
                this.renderCart();
            } else {
                this.showNotification(@json(__('Failed to load cart items')), 'error');
            }
        } catch (error) {
            console.error('Error loading cart:', error);
            this.showNotification(@json(__('Error loading cart')), 'error');
        }
    }

    renderCart() {
        const cartItemsContainer = document.getElementById('cart-items');
        const emptyCart = document.getElementById('empty-cart');
        const loginPrompt = document.getElementById('login-prompt');
        const cartCount = document.getElementById('cart-count');
        const checkoutBtn = document.getElementById('checkout-btn');

        if (this.cart.length === 0) {
            cartItemsContainer.style.display = 'none';
            emptyCart.style.display = 'block';
            loginPrompt.style.display = 'none';
            checkoutBtn.disabled = true;
            cartCount.textContent = '0';
            this.updateHeaderCartCount(0);
            this.updateSummary();
            return;
        }

        cartItemsContainer.style.display = 'flex';
        emptyCart.style.display = 'none';
        loginPrompt.style.display = 'none';
        checkoutBtn.disabled = false;

        const totalQuantity = this.cart.reduce((sum, item) => {
            const quantity = item.qty || item.quantity || 1;
            return sum + parseInt(quantity, 10);
        }, 0);
        cartCount.textContent = totalQuantity;
        this.updateHeaderCartCount(totalQuantity);

        cartItemsContainer.innerHTML = this.cart.map(item => {
            const product = item.product || item;
            const quantity = parseInt(item.qty || item.quantity || 1, 10);
            const itemId = item.id || item.product_id;
            const productImage = product.thumb_image
                ? `{{ asset('') }}${product.thumb_image}`
                : this.defaultProductImage;
            const unitPrice = parseFloat(item.unit_price ?? product.offer_price ?? product.price ?? 0);
            const lineTotal = parseFloat(item.line_total ?? (unitPrice * quantity));
            const productSlug = product.slug || '';
            const productUrl = productSlug ? `/product/${productSlug}` : '{{ route('products') }}';
            const variantsHtml = item.variants && Array.isArray(item.variants) && item.variants.length > 0
                ? `<div class="cart-line__variants">${item.variants.map(v => v.name || ((v.variant_name || '') + ': ' + (v.variant_value || ''))).filter(Boolean).join(' · ')}</div>`
                : '';

            return `
                <article class="cart-line" data-id="${itemId}">
                    <a href="${productUrl}" class="cart-line__media">
                        <img src="${productImage}" alt="${product.name}" onerror="this.src='${this.defaultProductImage}'">
                    </a>
                    <div class="cart-line__body">
                        <h3 class="cart-line__title">
                            <a href="${productUrl}">${product.name}</a>
                        </h3>
                        <div class="cart-line__meta">${product.category?.name || @json(__('Uncategorized'))}</div>
                        ${variantsHtml}
                        <div class="cart-line__price">${this.formatMoney(unitPrice)} ${@json(__('each'))}</div>
                    </div>
                    <div class="cart-line__actions">
                        <div class="cart-line__total">
                            <span>${@json(__('Line total'))}</span>
                            ${this.formatMoney(lineTotal)}
                        </div>
                        <div class="pd-qty-control">
                            <button type="button" class="qty-btn" data-item-id="${itemId}" data-action="decrease" aria-label="${@json(__('Decrease quantity'))}">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" value="${quantity}" min="1" class="qty-input" data-item-id="${itemId}" aria-label="${@json(__('Quantity'))}">
                            <button type="button" class="qty-btn" data-item-id="${itemId}" data-action="increase" aria-label="${@json(__('Increase quantity'))}">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button type="button" class="cart-remove-btn" onclick="cart.removeItem('${itemId}')" aria-label="${@json(__('Remove item'))}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </article>
            `;
        }).join('');

        this.updateSummary();
        this.attachQuantityEventListeners();
    }

    attachQuantityEventListeners() {
        document.querySelectorAll('.qty-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const btn = e.target.closest('.qty-btn');
                const itemId = btn.dataset.itemId;
                const action = btn.dataset.action;
                const input = document.querySelector(`.qty-input[data-item-id="${itemId}"]`);
                const currentQty = parseInt(input.value, 10);

                if (action === 'increase') {
                    this.updateQuantity(itemId, currentQty + 1);
                } else if (action === 'decrease') {
                    this.updateQuantity(itemId, currentQty - 1);
                }
            });
        });

        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const itemId = e.target.dataset.itemId;
                const newQty = parseInt(e.target.value, 10);
                this.updateQuantity(itemId, newQty);
            });
        });
    }

    async updateQuantity(id, quantity) {
        quantity = parseInt(quantity, 10);

        if (quantity < 1) {
            this.removeItem(id);
            return;
        }

        try {
            const response = await fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    cart_item_id: id,
                    quantity: quantity
                })
            });

            const data = await response.json();

            if (data.success) {
                this.loadCartItems();
                this.showNotification(@json(__('Cart updated successfully!')));
                this.updateHeaderCartCount(data.cart_count, data.cart_total);
            } else {
                this.showNotification(data.message || @json(__('Failed to update cart')), 'error');
            }
        } catch (error) {
            console.error('Error updating cart:', error);
            this.showNotification(@json(__('Error updating cart')), 'error');
        }
    }

    async removeItem(id) {
        try {
            const response = await fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    cart_item_id: id
                })
            });

            const data = await response.json();
            if (data.success) {
                this.loadCartItems();
                this.showNotification(@json(__('Item removed from cart!')));
                this.updateHeaderCartCount(data.cart_count, data.cart_total);
            } else {
                this.showNotification(data.message || @json(__('Failed to remove item')), 'error');
            }
        } catch (error) {
            console.error('Error removing item:', error);
            this.showNotification(@json(__('Error removing item')), 'error');
        }
    }

    updateSummary() {
        const subtotal = this.cart.reduce((sum, item) => {
            const product = item.product || item;
            const quantity = item.qty || item.quantity || 1;
            const price = parseFloat(product.offer_price || product.price || 0);
            return sum + (price * quantity);
        }, 0);

        document.getElementById('subtotal').textContent = this.formatMoney(subtotal);
        document.getElementById('total').textContent = this.formatMoney(subtotal);
        this.updateHeaderCartCount(null, subtotal);
    }

    proceedToCheckout() {
        if (this.cart.length === 0) {
            this.showNotification(@json(__('Your cart is empty!')), 'error');
            return;
        }

        window.location.href = '/checkout';
    }

    async loadRecommendedProducts() {
        const container = document.getElementById('recommended-products');

        try {
            const response = await fetch('/api/recommended-products');
            const data = await response.json();

            if (data.success && data.products && data.products.length) {
                container.innerHTML = data.products.map(product => {
                    const imageUrl = product.thumb_image
                        ? `{{ asset('') }}${product.thumb_image}`
                        : this.defaultProductImage;
                    const price = product.offer_price || product.price;
                    const availableStock = (product.qty || 0) - (product.sold_qty || 0);

                    return `
                        <div class="cart-recommend-item">
                            <img src="${imageUrl}" alt="${product.name}" onerror="this.src='${this.defaultProductImage}'">
                            <div>
                                <div class="cart-recommend-item__name">${product.name}</div>
                                <div class="cart-recommend-item__price">${this.formatMoney(price)}</div>
                            </div>
                            ${availableStock > 0
                                ? `<button type="button" class="cart-recommend-add" onclick="cart.addRecommendedToCart(${product.id}, this)" aria-label="Add ${product.name} to cart"><i class="fas fa-plus"></i></button>`
                                : `<span class="badge bg-warning text-dark">${@json(__('Out'))}</span>`
                            }
                        </div>
                    `;
                }).join('');
            } else {
                container.innerHTML = '<p class="cart-recommend-empty mb-0">' + @json(__('No recommendations right now.')) + '</p>';
            }
        } catch (error) {
            console.error('Error loading recommended products:', error);
            container.innerHTML = '<p class="cart-recommend-empty mb-0">' + @json(__('Unable to load recommendations.')) + '</p>';
        }
    }

    async addRecommendedToCart(productId, buttonEl) {
        const button = buttonEl;
        const originalContent = button.innerHTML;

        try {
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', 1);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            const response = await fetch('/cart/add', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification(data.message || @json(__('Product added to cart!')), 'success');
                if (data.cart_count !== undefined) {
                    this.updateHeaderCartCount(data.cart_count, data.cart_total);
                }
                this.loadCartItems();
            } else {
                this.showNotification(data.message || @json(__('Failed to add product to cart')), 'error');
            }
        } catch (error) {
            console.error('Error adding product to cart:', error);
            this.showNotification(@json(__('An error occurred. Please try again.')), 'error');
        } finally {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    }

    updateHeaderCartCount(count = null, total = null) {
        let value = 0;
        if (count !== null) {
            value = count;
        } else {
            value = this.cart.reduce((sum, item) => {
                const quantity = item.qty || item.quantity || 1;
                return sum + parseInt(quantity, 10);
            }, 0);
        }

        let cartTotal = total;
        if (cartTotal === null) {
            cartTotal = this.cart.reduce((sum, item) => {
                const product = item.product || item;
                const quantity = item.qty || item.quantity || 1;
                const price = parseFloat(product.offer_price || product.price || 0);
                return sum + (price * quantity);
            }, 0);
        }

        if (typeof updateCartDisplay === 'function') {
            updateCartDisplay(value, cartTotal);
            return;
        }

        document.querySelectorAll('.cart-count').forEach(el => {
            el.textContent = value;
            if (value > 0) {
                el.classList.remove('d-none');
            } else {
                el.classList.add('d-none');
            }
        });
    }

    showNotification(message, type = 'success') {
        if (window.showNotification) {
            window.showNotification(message, type);
            return;
        }
        alert(message);
    }
}

const cart = new ShoppingCart();
</script>
@endsection
