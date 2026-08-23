// Frontend JavaScript for Nuter Guru

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initScrollAnimations();
    initProductFilters();
    initCartFunctionality();
    initCartDrawer();
    initWishlistFunctionality();
    initSearchFunctionality();
    initImageZoom();
    initQuantityControls();
    initSmoothScrolling();
    initMobileSearchToggle();
    initHomeCategoryAutoSlide();
});

// Scroll Animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Observe all fade-in elements
    document.querySelectorAll('.fade-in').forEach(el => {
        observer.observe(el);
    });
}

// Product Filters
function initProductFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const sortSelect = document.getElementById('sortSelect');
    const priceRange = document.getElementById('priceRange');
    const priceDisplay = document.getElementById('priceDisplay');

    // Filter by category
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            filterProducts(category);
            
            // Update active state
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Sort products
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortBy = this.value;
            sortProducts(sortBy);
        });
    }

    // Price range filter
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            const maxPrice = this.value;
            if (priceDisplay) {
                priceDisplay.textContent = `$0 - $${maxPrice}`;
            }
            filterByPrice(maxPrice);
        });
    }
}

// Filter products by category
function filterProducts(category) {
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        if (category === 'all' || product.dataset.category === category) {
            product.style.display = 'block';
            product.classList.add('fade-in');
        } else {
            product.style.display = 'none';
        }
    });
}

// Sort products
function sortProducts(sortBy) {
    const container = document.querySelector('.products-container');
    if (!container) return;
    
    const products = Array.from(container.querySelectorAll('.product-card'));
    
    products.sort((a, b) => {
        switch (sortBy) {
            case 'price-low':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'price-high':
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            case 'name':
                return a.dataset.name.localeCompare(b.dataset.name);
            case 'newest':
                return new Date(b.dataset.date) - new Date(a.dataset.date);
            default:
                return 0;
        }
    });
    
    // Re-append sorted products
    products.forEach(product => container.appendChild(product));
}

// Filter by price
function filterByPrice(maxPrice) {
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const price = parseFloat(product.dataset.price);
        if (price <= maxPrice) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

// Cart Functionality
function initCartFunctionality() {
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.add-to-cart');
        if (!btn || btn.disabled) {
            return;
        }

        e.preventDefault();

        addToCart({
            id: btn.dataset.productId,
            name: btn.dataset.productName,
            price: btn.dataset.productPrice,
            image: btn.dataset.productImage,
            quantity: 1
        }, btn);
    });
}

// Add item to cart
function addToCart(product, buttonEl) {
    const button = buttonEl || (typeof event !== 'undefined' && event?.target
        ? event.target.closest('.add-to-cart')
        : null);
    if (!button) {
        return;
    }
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;

    // Prepare data
    const formData = new FormData();
    formData.append('product_id', product.id);
    formData.append('quantity', product.quantity || 1);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    // Make AJAX request
    fetch('/cart/add', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            // Immediately update cart count using server response
            if (typeof data.cart_count !== 'undefined') {
                updateCartDisplay(data.cart_count, data.cart_total);
            }
            // Update cart count from server (resync)
            updateCartCount();
            // Open cart drawer on successful add
            if (typeof window.openCartDrawer === 'function') {
                window.openCartDrawer();
            }
        } else {
            showNotification(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'danger');
    })
    .finally(() => {
        // Restore button state
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Update cart badge count and header total from server
function updateCartDisplay(count, total) {
    const countNum = parseInt(count, 10) || 0;
    const totalNum = parseFloat(total) || 0;

    document.querySelectorAll('.cart-count').forEach(element => {
        element.textContent = countNum;
        if (countNum > 0) {
            element.classList.remove('d-none');
        } else {
            element.classList.add('d-none');
        }
    });

    document.querySelectorAll('.cart-total-amount').forEach(element => {
        element.textContent = formatCartAmount(totalNum);
    });
}

function formatCartAmount(amount) {
    const value = parseFloat(amount) || 0;
    return Number.isInteger(value) ? String(value) : value.toFixed(2);
}

// Cart drawer — slide-in panel from the right
function initCartDrawer() {
    const drawerEl = document.getElementById('cartDrawer');
    if (!drawerEl || typeof bootstrap === 'undefined') {
        return;
    }

    window.cartDrawerInstance = bootstrap.Offcanvas.getOrCreateInstance(drawerEl);

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('.js-open-cart-drawer');
        if (!trigger) {
            return;
        }
        e.preventDefault();
        window.openCartDrawer();
    });

    drawerEl.addEventListener('click', function (e) {
        const qtyBtn = e.target.closest('.cart-drawer__qty-btn');
        if (qtyBtn) {
            e.preventDefault();
            const itemId = qtyBtn.dataset.itemId;
            const action = qtyBtn.dataset.action;
            const input = drawerEl.querySelector('.cart-drawer__qty-input[data-item-id="' + itemId + '"]');
            const currentQty = parseInt(input?.value, 10) || 1;

            if (action === 'increase') {
                updateCartDrawerQuantity(itemId, currentQty + 1);
            } else if (action === 'decrease') {
                updateCartDrawerQuantity(itemId, currentQty - 1);
            }
            return;
        }

        const removeBtn = e.target.closest('.cart-drawer__remove');
        if (removeBtn) {
            e.preventDefault();
            removeCartDrawerItem(removeBtn.dataset.itemId);
        }
    });

    drawerEl.addEventListener('change', function (e) {
        const input = e.target.closest('.cart-drawer__qty-input');
        if (!input) {
            return;
        }
        const qty = parseInt(input.value, 10);
        if (qty > 0) {
            updateCartDrawerQuantity(input.dataset.itemId, qty);
        }
    });
}

window.openCartDrawer = function openCartDrawer() {
    const drawerEl = document.getElementById('cartDrawer');
    if (!drawerEl || typeof bootstrap === 'undefined') {
        return;
    }

    if (!window.cartDrawerInstance) {
        window.cartDrawerInstance = bootstrap.Offcanvas.getOrCreateInstance(drawerEl);
    }

    loadCartDrawerItems().then(function () {
        window.cartDrawerInstance.show();
    });
};

function getCartDrawerCurrency() {
    const drawerEl = document.getElementById('cartDrawer');
    return (drawerEl && drawerEl.dataset.currency)
        || window.__cartCurrency
        || '$';
}

function formatCartDrawerMoney(amount) {
    const currency = getCartDrawerCurrency();
    return currency + parseFloat(amount || 0).toFixed(2);
}

function renderCartDrawerItemHtml(item) {
    const defaultImage = window.__defaultProductImage || '/frontend/images/default-product.svg';
    const product = item.product || item;
    const quantity = parseInt(item.qty || item.quantity || 1, 10);
    const itemId = item.id || item.product_id;
    const productImage = product.thumb_image
        ? (product.thumb_image.startsWith('http') ? product.thumb_image : '/' + product.thumb_image.replace(/^\//, ''))
        : defaultImage;
    const unitPrice = parseFloat(product.offer_price || product.price || 0);
    const lineTotal = unitPrice * quantity;
    const productSlug = product.slug || '';
    const productUrl = productSlug ? '/product/' + productSlug : '/products';

    return (
        '<article class="cart-drawer__item" data-id="' + escapeHtml(String(itemId)) + '" data-unit-price="' + unitPrice + '">' +
            '<a href="' + escapeHtml(productUrl) + '" class="cart-drawer__item-media" data-bs-dismiss="offcanvas">' +
                '<img src="' + escapeHtml(productImage) + '" alt="' + escapeHtml(product.name || '') + '" loading="lazy" onerror="this.src=\'' + defaultImage + '\'">' +
            '</a>' +
            '<div class="cart-drawer__item-body">' +
                '<h3 class="cart-drawer__item-title">' +
                    '<a href="' + escapeHtml(productUrl) + '" data-bs-dismiss="offcanvas">' + escapeHtml(product.name || '') + '</a>' +
                '</h3>' +
                '<div class="cart-drawer__item-price">' + formatCartDrawerMoney(unitPrice) + '</div>' +
                '<div class="cart-drawer__item-actions">' +
                    '<div class="cart-drawer__qty">' +
                        '<button type="button" class="cart-drawer__qty-btn" data-item-id="' + escapeHtml(String(itemId)) + '" data-action="decrease" aria-label="Decrease quantity">' +
                            '<i class="fas fa-minus"></i>' +
                        '</button>' +
                        '<input type="number" class="cart-drawer__qty-input" value="' + quantity + '" min="1" data-item-id="' + escapeHtml(String(itemId)) + '" aria-label="Quantity">' +
                        '<button type="button" class="cart-drawer__qty-btn" data-item-id="' + escapeHtml(String(itemId)) + '" data-action="increase" aria-label="Increase quantity">' +
                            '<i class="fas fa-plus"></i>' +
                        '</button>' +
                    '</div>' +
                    '<button type="button" class="cart-drawer__remove" data-item-id="' + escapeHtml(String(itemId)) + '" aria-label="Remove item">' +
                        '<i class="fas fa-trash-alt"></i>' +
                    '</button>' +
                '</div>' +
            '</div>' +
            '<div class="cart-drawer__item-total">' + formatCartDrawerMoney(lineTotal) + '</div>' +
        '</article>'
    );
}

function refreshCartDrawerSummary(cartCount, cartTotal) {
    const countEl = document.getElementById('cartDrawerCount');
    const subtotalEl = document.getElementById('cartDrawerSubtotal');
    const itemsEl = document.getElementById('cartDrawerItems');

    let totalQty = 0;
    let subtotal = 0;

    if (itemsEl) {
        itemsEl.querySelectorAll('.cart-drawer__item').forEach(function (itemEl) {
            const qty = parseInt(itemEl.querySelector('.cart-drawer__qty-input')?.value, 10) || 0;
            const unitPrice = parseFloat(itemEl.dataset.unitPrice) || 0;
            totalQty += qty;
            subtotal += unitPrice * qty;
        });
    }

    if (countEl) {
        countEl.textContent = String(typeof cartCount !== 'undefined' ? cartCount : totalQty);
    }
    if (subtotalEl) {
        subtotalEl.textContent = formatCartDrawerMoney(
            typeof cartTotal !== 'undefined' ? cartTotal : subtotal
        );
    }
}

function showCartDrawerEmptyState() {
    const emptyEl = document.getElementById('cartDrawerEmpty');
    const itemsEl = document.getElementById('cartDrawerItems');
    const footerEl = document.getElementById('cartDrawerFooter');
    const countEl = document.getElementById('cartDrawerCount');

    if (itemsEl) {
        itemsEl.innerHTML = '';
        itemsEl.hidden = true;
    }
    if (emptyEl) {
        emptyEl.hidden = false;
    }
    if (footerEl) {
        footerEl.hidden = true;
    }
    if (countEl) {
        countEl.textContent = '0';
    }
}

function applyCartDrawerItems(cartItems, cartTotal) {
    const emptyEl = document.getElementById('cartDrawerEmpty');
    const itemsEl = document.getElementById('cartDrawerItems');
    const footerEl = document.getElementById('cartDrawerFooter');

    if (!itemsEl) {
        return;
    }

    if (!cartItems.length) {
        showCartDrawerEmptyState();
        return;
    }

    itemsEl.innerHTML = cartItems.map(renderCartDrawerItemHtml).join('');
    itemsEl.hidden = false;
    if (emptyEl) {
        emptyEl.hidden = true;
    }
    if (footerEl) {
        footerEl.hidden = false;
    }

    refreshCartDrawerSummary(undefined, cartTotal);
}

function updateCartDrawerItemDom(itemId, quantity) {
    const itemEl = document.querySelector('.cart-drawer__item[data-id="' + itemId + '"]');
    if (!itemEl) {
        return null;
    }

    const input = itemEl.querySelector('.cart-drawer__qty-input');
    const totalEl = itemEl.querySelector('.cart-drawer__item-total');
    const unitPrice = parseFloat(itemEl.dataset.unitPrice) || 0;
    const previousQty = parseInt(input?.value, 10) || 1;

    if (input) {
        input.value = quantity;
    }
    if (totalEl) {
        totalEl.textContent = formatCartDrawerMoney(unitPrice * quantity);
    }

    refreshCartDrawerSummary();

    return { itemEl, previousQty, unitPrice };
}

function loadCartDrawerItems(options) {
    const silent = options && options.silent;
    const loadingEl = document.getElementById('cartDrawerLoading');
    const emptyEl = document.getElementById('cartDrawerEmpty');
    const itemsEl = document.getElementById('cartDrawerItems');
    const footerEl = document.getElementById('cartDrawerFooter');

    if (!loadingEl || !emptyEl || !itemsEl) {
        return Promise.resolve();
    }

    if (!silent) {
        loadingEl.hidden = false;
        emptyEl.hidden = true;
        itemsEl.hidden = true;
        if (footerEl) {
            footerEl.hidden = true;
        }
    }

    return fetch('/cart/items', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            loadingEl.hidden = true;

            if (!data.success) {
                if (!silent) {
                    showCartDrawerEmptyState();
                }
                return;
            }

            const cartItems = Array.isArray(data.cart_items)
                ? data.cart_items
                : (data.cart_items && typeof data.cart_items === 'object' && data.cart_items.data)
                    ? data.cart_items.data
                    : [];

            if (typeof data.cart_count !== 'undefined') {
                updateCartDisplay(data.cart_count, data.cart_total);
            }

            applyCartDrawerItems(cartItems, data.cart_total);
        })
        .catch(function (error) {
            console.error('Error loading cart drawer:', error);
            loadingEl.hidden = true;
            if (!silent) {
                showCartDrawerEmptyState();
            }
        });
}

function updateCartDrawerQuantity(itemId, quantity) {
    quantity = parseInt(quantity, 10);

    if (quantity < 1) {
        removeCartDrawerItem(itemId);
        return;
    }

    const domState = updateCartDrawerItemDom(itemId, quantity);
    if (!domState) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        return;
    }

    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            cart_item_id: itemId,
            quantity: quantity
        })
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                updateCartDisplay(data.cart_count, data.cart_total);
                refreshCartDrawerSummary(data.cart_count, data.cart_total);
            } else {
                updateCartDrawerItemDom(itemId, domState.previousQty);
                showNotification(data.message || 'Failed to update cart', 'danger');
            }
        })
        .catch(function (error) {
            console.error('Error updating cart:', error);
            updateCartDrawerItemDom(itemId, domState.previousQty);
            showNotification('Error updating cart', 'danger');
        });
}

function removeCartDrawerItem(itemId) {
    const itemEl = document.querySelector('.cart-drawer__item[data-id="' + itemId + '"]');
    if (itemEl) {
        itemEl.remove();
    }

    const itemsEl = document.getElementById('cartDrawerItems');
    if (itemsEl && !itemsEl.querySelector('.cart-drawer__item')) {
        showCartDrawerEmptyState();
    } else {
        refreshCartDrawerSummary();
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        return;
    }

    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            cart_item_id: itemId
        })
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.success) {
                updateCartDisplay(data.cart_count, data.cart_total);
                refreshCartDrawerSummary(data.cart_count, data.cart_total);
                showNotification(data.message || 'Item removed from cart', 'success');
            } else {
                loadCartDrawerItems({ silent: true });
                showNotification(data.message || 'Failed to remove item', 'danger');
            }
        })
        .catch(function (error) {
            console.error('Error removing item:', error);
            loadCartDrawerItems({ silent: true });
            showNotification('Error removing item', 'danger');
        });
}

// Update cart count from server
function updateCartCount() {
    fetch('/cart/count?ts=' + Date.now(), {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartDisplay(data.cart_count, data.cart_total);
        }
    })
    .catch(error => {
        console.error('Error fetching cart count:', error);
        updateCartDisplay(0, 0);
    });
}

// Wishlist Functionality
function initWishlistFunctionality() {
    const wishlistBtns = document.querySelectorAll('.add-to-wishlist');
    
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const isWishlisted = this.classList.contains('wishlisted');
            
            if (isWishlisted) {
                removeFromWishlist(productId);
                this.classList.remove('wishlisted');
                this.innerHTML = '<i class="far fa-heart"></i>';
                showNotification('Removed from wishlist', 'info');
            } else {
                addToWishlist(productId);
                this.classList.add('wishlisted');
                this.innerHTML = '<i class="fas fa-heart"></i>';
                showNotification('Added to wishlist!', 'success');
            }
            
            updateWishlistCount();
        });
    });
}

// Add to wishlist
function addToWishlist(productId) {
    // Check if user is authenticated by looking for CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (csrfToken) {
        // User is authenticated, save to database
        fetch('/user/wishlist/add', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Added to wishlist!', 'success');
            } else {
                showNotification(data.message || 'Failed to add to wishlist', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to add to wishlist', 'error');
        });
    } else {
        // User is not authenticated, use localStorage
        let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        
        if (!wishlist.includes(productId)) {
            wishlist.push(productId);
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
            showNotification('Added to wishlist!', 'success');
        } else {
            showNotification('Product already in wishlist', 'info');
        }
    }
}

// Remove from wishlist
function removeFromWishlist(productId) {
    // Check if user is authenticated by looking for CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (csrfToken) {
        // User is authenticated, remove from database
        fetch(`/user/wishlist/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Removed from wishlist', 'info');
            } else {
                showNotification(data.message || 'Failed to remove from wishlist', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to remove from wishlist', 'error');
        });
    } else {
        // User is not authenticated, use localStorage
        let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
        wishlist = wishlist.filter(id => id !== productId);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        showNotification('Removed from wishlist', 'info');
    }
}

// Update wishlist count
function updateWishlistCount() {
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    const wishlistCountElements = document.querySelectorAll('.wishlist-count');
    
    wishlistCountElements.forEach(element => {
        element.textContent = wishlist.length;
    });
}

// Search Functionality
function initSearchFunctionality() {
    const searchUrl = window.__searchProductsUrl || '/api/search-products';
    const inputs = document.querySelectorAll('.js-product-search');

    if (!inputs.length) {
        return;
    }

    inputs.forEach(function (searchInput) {
        const wrap = searchInput.closest('.organic-search') || searchInput.closest('.mobile-app-menu__search');
        const searchSuggestions = wrap ? wrap.querySelector('.js-search-suggestions') : null;

        if (!searchSuggestions) {
            return;
        }

        let searchTimeout;
        let activeController = null;

        const hideSuggestions = () => {
            searchSuggestions.innerHTML = '';
            searchSuggestions.hidden = true;
            searchSuggestions.classList.remove('is-visible');
        };

        const showSuggestions = () => {
            searchSuggestions.hidden = false;
            searchSuggestions.classList.add('is-visible');
        };

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (activeController) {
                activeController.abort();
                activeController = null;
            }

            if (query.length < 2) {
                hideSuggestions();
                return;
            }

            searchTimeout = setTimeout(() => {
                activeController = new AbortController();
                fetchSearchSuggestions(
                    query,
                    searchUrl,
                    searchSuggestions,
                    showSuggestions,
                    hideSuggestions,
                    activeController
                );
            }, 280);
        });

        searchInput.addEventListener('focus', function () {
            const query = this.value.trim();
            if (query.length >= 2 && searchSuggestions.innerHTML.trim() !== '') {
                showSuggestions();
            }
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) {
                hideSuggestions();
            }
        });
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function fetchSearchSuggestions(query, searchUrl, container, showSuggestions, hideSuggestions, controller) {
    container.innerHTML = '<div class="search-suggestions__loading">Searching...</div>';
    showSuggestions();

    fetch(searchUrl + '?q=' + encodeURIComponent(query), {
        headers: { 'Accept': 'application/json' },
        signal: controller ? controller.signal : undefined,
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Search failed');
            }
            return response.json();
        })
        .then(function (data) {
            displaySearchSuggestions(data.products || [], container, showSuggestions, hideSuggestions);
        })
        .catch(function (error) {
            if (error.name === 'AbortError') {
                return;
            }
            hideSuggestions();
        });
}

function displaySearchSuggestions(products, container, showSuggestions, hideSuggestions) {
    if (!container) {
        return;
    }

    if (!products.length) {
        container.innerHTML = '<div class="search-suggestions__empty">No products found</div>';
        showSuggestions();
        return;
    }

    const html = products.map(function (product) {
        return (
            '<a href="' + escapeHtml(product.url) + '" class="search-suggestions__item" role="option">' +
                '<img src="' + escapeHtml(product.image) + '" alt="" class="search-suggestions__thumb" loading="lazy" onerror="this.src=\'/frontend/images/default-product.svg\'">' +
                '<span class="search-suggestions__body">' +
                    '<span class="search-suggestions__name">' + escapeHtml(product.name) + '</span>' +
                    '<span class="search-suggestions__price">' + escapeHtml(product.currency) + escapeHtml(product.price) + '</span>' +
                '</span>' +
            '</a>'
        );
    }).join('');

    container.innerHTML = html;
    showSuggestions();
}

function initMobileSearchToggle() {
    const header = document.getElementById('organicHeader');
    const toggle = document.getElementById('organicSearchToggle');
    const searchInput = document.querySelector('.organic-search__input');

    if (!header || !toggle) {
        return;
    }

    const setOpen = (open) => {
        header.classList.toggle('is-search-open', open);
        toggle.classList.toggle('is-active', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');

        if (open && searchInput) {
            window.requestAnimationFrame(() => searchInput.focus());
        }
    };

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        setOpen(!header.classList.contains('is-search-open'));
    });

    document.addEventListener('click', function (e) {
        if (!header.classList.contains('is-search-open')) {
            return;
        }

        if (e.target.closest('.organic-search-col') || e.target.closest('#organicSearchToggle')) {
            return;
        }

        setOpen(false);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            setOpen(false);
        }
    });

    if (searchInput && searchInput.value.trim() !== '' && window.matchMedia('(max-width: 991.98px)').matches) {
        setOpen(true);
    }
}

function initHomeCategoryAutoSlide() {
    const slider = document.getElementById('homeCategorySlider');
    if (!slider) {
        return;
    }

    const mobileQuery = window.matchMedia('(max-width: 991.98px)');
    const reduceMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
    let intervalId = null;
    let paused = false;
    let resumeTimeout = null;

    const getScrollStep = () => {
        const cards = slider.querySelectorAll('.home-category-card');
        if (cards.length < 2) {
            return slider.clientWidth * 0.75;
        }
        return Math.max(cards[1].offsetLeft - cards[0].offsetLeft, 80);
    };

    const canAutoSlide = () => {
        return mobileQuery.matches
            && !reduceMotionQuery.matches
            && slider.scrollWidth > slider.clientWidth + 4;
    };

    const tick = () => {
        if (paused || !canAutoSlide()) {
            return;
        }

        const maxScroll = slider.scrollWidth - slider.clientWidth;
        const step = getScrollStep();
        const next = slider.scrollLeft + step;

        if (next >= maxScroll - 4) {
            slider.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            slider.scrollTo({ left: next, behavior: 'smooth' });
        }
    };

    const stop = () => {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
    };

    const start = () => {
        stop();
        if (!canAutoSlide()) {
            return;
        }
        intervalId = window.setInterval(tick, 3200);
    };

    const pauseTemporarily = (ms) => {
        paused = true;
        if (resumeTimeout) {
            clearTimeout(resumeTimeout);
        }
        resumeTimeout = window.setTimeout(function () {
            paused = false;
        }, ms || 5000);
    };

    slider.addEventListener('touchstart', function () {
        pauseTemporarily(6000);
    }, { passive: true });

    slider.addEventListener('pointerdown', function () {
        pauseTemporarily(6000);
    });

    slider.addEventListener('wheel', function () {
        pauseTemporarily(4000);
    }, { passive: true });

    const onLayoutChange = () => start();

    if (mobileQuery.addEventListener) {
        mobileQuery.addEventListener('change', onLayoutChange);
    }
    if (reduceMotionQuery.addEventListener) {
        reduceMotionQuery.addEventListener('change', onLayoutChange);
    }

    window.addEventListener('resize', onLayoutChange);
    window.addEventListener('load', onLayoutChange);

    start();
}

// Image Zoom
function initImageZoom() {
    const zoomImages = document.querySelectorAll('.zoom-image');
    
    zoomImages.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.2)';
        });
        
        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
}

// Quantity Controls
function initQuantityControls() {
    const quantityControls = document.querySelectorAll('.quantity-control');
    
    quantityControls.forEach(control => {
        const minusBtn = control.querySelector('.quantity-minus');
        const plusBtn = control.querySelector('.quantity-plus');
        const input = control.querySelector('.quantity-input');
        
        if (minusBtn && plusBtn && input) {
            minusBtn.addEventListener('click', function() {
                const currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                }
            });
            
            plusBtn.addEventListener('click', function() {
                const currentValue = parseInt(input.value);
                const maxValue = parseInt(input.getAttribute('max')) || 999;
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                }
            });
        }
    });
}

// Smooth Scrolling
function initSmoothScrolling() {
    const scrollLinks = document.querySelectorAll('a[href^="#"]');
    
    scrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Professional toast notifications
(function () {
    const TOAST_ICONS = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        danger: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };

    const TOAST_TITLES = {
        success: 'Success',
        error: 'Error',
        danger: 'Error',
        warning: 'Warning',
        info: 'Info'
    };

    function ensureToastContainer() {
        let container = document.getElementById('toast-container');
        if (container) return container;

        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'app-toast-container';
        container.setAttribute('aria-live', 'polite');
        container.setAttribute('aria-atomic', 'true');
        document.body.appendChild(container);

        if (!document.getElementById('app-toast-styles')) {
            const style = document.createElement('style');
            style.id = 'app-toast-styles';
            style.textContent = `
                .app-toast-container {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 10800;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    width: min(380px, calc(100vw - 32px));
                    pointer-events: none;
                }
                .app-toast {
                    pointer-events: auto;
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    padding: 14px 16px;
                    border-radius: 12px;
                    background: #fff;
                    border: 1px solid #ebe7f2;
                    box-shadow: 0 12px 30px rgba(74, 74, 92, 0.14);
                    transform: translateX(24px);
                    opacity: 0;
                    transition: transform .25s ease, opacity .25s ease;
                    overflow: hidden;
                    position: relative;
                }
                .app-toast.is-visible {
                    transform: translateX(0);
                    opacity: 1;
                }
                .app-toast.is-hiding {
                    transform: translateX(24px);
                    opacity: 0;
                }
                .app-toast__icon {
                    width: 38px;
                    height: 38px;
                    border-radius: 10px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                    font-size: 1rem;
                }
                .app-toast__body { flex: 1; min-width: 0; }
                .app-toast__title {
                    font-size: 0.92rem;
                    font-weight: 700;
                    color: #4A4A5C;
                    margin: 0 0 2px;
                    line-height: 1.2;
                }
                .app-toast__message {
                    font-size: 0.88rem;
                    color: #6b6580;
                    margin: 0;
                    line-height: 1.45;
                    word-break: break-word;
                }
                .app-toast__close {
                    border: 0;
                    background: transparent;
                    color: #9a94a8;
                    width: 28px;
                    height: 28px;
                    border-radius: 8px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    flex-shrink: 0;
                }
                .app-toast__close:hover { background: #f4f1f8; color: #4A4A5C; }
                .app-toast__progress {
                    position: absolute;
                    left: 0;
                    bottom: 0;
                    height: 3px;
                    width: 100%;
                    transform-origin: left center;
                    animation: appToastProgress linear forwards;
                }
                .app-toast--success .app-toast__icon { background: rgba(25,135,84,.12); color: #198754; }
                .app-toast--success .app-toast__progress { background: #198754; }
                .app-toast--error .app-toast__icon,
                .app-toast--danger .app-toast__icon { background: rgba(220,53,69,.12); color: #dc3545; }
                .app-toast--error .app-toast__progress,
                .app-toast--danger .app-toast__progress { background: #dc3545; }
                .app-toast--warning .app-toast__icon { background: rgba(255,193,7,.18); color: #c79100; }
                .app-toast--warning .app-toast__progress { background: #ffc107; }
                .app-toast--info .app-toast__icon { background: rgba(139,123,168,.14); color: #8B7BA8; }
                .app-toast--info .app-toast__progress { background: #8B7BA8; }
                @keyframes appToastProgress {
                    from { transform: scaleX(1); }
                    to { transform: scaleX(0); }
                }
                @media (max-width: 575.98px) {
                    .app-toast-container {
                        top: 12px;
                        right: 12px;
                        left: 12px;
                        width: auto;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        return container;
    }

    function normalizeType(type) {
        const t = String(type || 'success').toLowerCase();
        if (t === 'danger') return 'error';
        if (['success', 'error', 'warning', 'info'].includes(t)) return t;
        return 'info';
    }

    window.showNotification = function showNotification(message, type = 'success', options = {}) {
        const normalized = normalizeType(type);
        const duration = typeof options.duration === 'number' ? options.duration : 4200;
        const container = ensureToastContainer();

        const toast = document.createElement('div');
        toast.className = `app-toast app-toast--${normalized}`;
        toast.setAttribute('role', 'alert');

        const safeMessage = String(message || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        toast.innerHTML = `
            <span class="app-toast__icon"><i class="${TOAST_ICONS[normalized]}"></i></span>
            <div class="app-toast__body">
                <p class="app-toast__title">${TOAST_TITLES[normalized]}</p>
                <p class="app-toast__message">${safeMessage}</p>
            </div>
            <button type="button" class="app-toast__close" aria-label="Close"><i class="fas fa-times"></i></button>
            <span class="app-toast__progress" style="animation-duration:${duration}ms"></span>
        `;

        container.appendChild(toast);
        requestAnimationFrame(() => toast.classList.add('is-visible'));

        const removeToast = () => {
            toast.classList.add('is-hiding');
            toast.classList.remove('is-visible');
            setTimeout(() => {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 250);
        };

        const timer = setTimeout(removeToast, duration);
        toast.querySelector('.app-toast__close').addEventListener('click', () => {
            clearTimeout(timer);
            removeToast();
        });

        return toast;
    };

    window.hideNotification = function hideNotification() {
        document.querySelectorAll('.app-toast').forEach((toast) => {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        });
    };
})();

// Keep legacy function name available in non-window scope
function showNotification(message, type = 'success', options = {}) {
    return window.showNotification(message, type, options);
}

function hideNotification() {
    return window.hideNotification();
}

// Price range dual-handle slider (products, category, brand pages)
window.PriceRangeFilter = {
    sync(wrap) {
        if (!wrap) {
            return;
        }

        const minRange = wrap.querySelector('.price-range-slider__input--min');
        const maxRange = wrap.querySelector('.price-range-slider__input--max');
        const fill = wrap.querySelector('.price-range-slider__fill');
        const minLabel = wrap.querySelector('.price-range-slider__min-label');
        const maxLabel = wrap.querySelector('.price-range-slider__max-label');

        if (!minRange || !maxRange || !fill) {
            return;
        }

        const floor = parseInt(wrap.dataset.floor, 10);
        const ceil = parseInt(wrap.dataset.ceil, 10);
        let minVal = parseInt(minRange.value, 10);
        let maxVal = parseInt(maxRange.value, 10);

        if (minVal > maxVal) {
            if (document.activeElement === minRange) {
                maxVal = minVal;
                maxRange.value = maxVal;
            } else {
                minVal = maxVal;
                minRange.value = minVal;
            }
        }

        const range = ceil - floor || 1;
        const left = ((minVal - floor) / range) * 100;
        const right = ((maxVal - floor) / range) * 100;

        fill.style.left = left + '%';
        fill.style.width = Math.max(0, right - left) + '%';

        const currency = wrap.dataset.currency || '';
        if (minLabel) {
            minLabel.textContent = currency + Number(minVal).toLocaleString();
        }
        if (maxLabel) {
            maxLabel.textContent = currency + Number(maxVal).toLocaleString();
        }
    },

    getParams(wrap) {
        if (!wrap) {
            return { min_price: null, max_price: null };
        }

        const minRange = wrap.querySelector('.price-range-slider__input--min');
        const maxRange = wrap.querySelector('.price-range-slider__input--max');

        if (!minRange || !maxRange) {
            return { min_price: null, max_price: null };
        }

        const floor = parseInt(wrap.dataset.floor, 10);
        const ceil = parseInt(wrap.dataset.ceil, 10);
        const minVal = parseInt(minRange.value, 10);
        const maxVal = parseInt(maxRange.value, 10);

        return {
            min_price: minVal > floor ? minVal : null,
            max_price: maxVal < ceil ? maxVal : null,
        };
    },

    init(wrap) {
        if (!wrap || wrap.dataset.priceRangeReady === '1') {
            return;
        }

        wrap.dataset.priceRangeReady = '1';
        const minRange = wrap.querySelector('.price-range-slider__input--min');
        const maxRange = wrap.querySelector('.price-range-slider__input--max');

        if (minRange) {
            minRange.addEventListener('input', () => this.sync(wrap));
        }
        if (maxRange) {
            maxRange.addEventListener('input', () => this.sync(wrap));
        }

        this.sync(wrap);
    },

    initAll() {
        document.querySelectorAll('[data-price-range-slider]').forEach((wrap) => {
            this.init(wrap);
        });
    },
};

// Initialize cart and wishlist counts on page load
document.addEventListener('DOMContentLoaded', function() {
    if (window.PriceRangeFilter) {
        window.PriceRangeFilter.initAll();
    }

    updateCartCount();
    updateWishlistCount();
    
    // Mark wishlisted items
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    document.querySelectorAll('.add-to-wishlist').forEach(btn => {
        const productId = btn.dataset.productId;
        if (wishlist.includes(productId)) {
            btn.classList.add('wishlisted');
            btn.innerHTML = '<i class="fas fa-heart"></i>';
        }
    });
});

// Utility Functions
function formatPrice(price) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}