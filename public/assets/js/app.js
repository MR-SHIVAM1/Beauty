(function () {
    'use strict';

    /* ---------- Toasts ---------- */
    const toastStack = document.getElementById('toastStack');

    function iconFor(type) {
        return type === 'error'
            ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'
            : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
    }

    window.showToast = function (type, message) {
        if (!toastStack) return;
        const el = document.createElement('div');
        el.className = 'toast ' + type;
        el.innerHTML = iconFor(type) + '<p>' + message + '</p>';
        toastStack.appendChild(el);
        setTimeout(() => {
            el.style.animation = 'toastOut 0.35s ease forwards';
            setTimeout(() => el.remove(), 350);
        }, 3800);
    };

    document.querySelectorAll('.toast').forEach((el) => {
        setTimeout(() => {
            el.style.animation = 'toastOut 0.35s ease forwards';
            setTimeout(() => el.remove(), 350);
        }, 4200);
    });

    /* ---------- Mobile nav ---------- */
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', () => mobileMenu.classList.toggle('open'));
    }

    /* ---------- User dropdown ---------- */
    document.querySelectorAll('.user-menu').forEach((menu) => {
        const chip = menu.querySelector('.user-chip');
        chip && chip.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.user-menu.open').forEach((m) => m !== menu && m.classList.remove('open'));
            menu.classList.toggle('open');
        });
    });
    document.addEventListener('click', () => {
        document.querySelectorAll('.user-menu.open').forEach((m) => m.classList.remove('open'));
    });

    /* ---------- Badge helpers ---------- */
    function bumpBadge(selector, value) {
        document.querySelectorAll(selector).forEach((el) => {
            el.textContent = value;
            el.style.display = value > 0 ? 'flex' : 'none';
        });
    }

    /* ---------- AJAX wishlist toggle ---------- */
    document.querySelectorAll('.js-wishlist-form').forEach((form) => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = form.querySelector('.wishlist-btn');
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'fetch' },
                body: new FormData(form),
            })
                .then((r) => r.ok ? r.json() : Promise.reject())
                .then((data) => {
                    if (btn) {
                        btn.classList.toggle('active', data.added);
                        void btn.offsetWidth;
                    }
                    bumpBadge('.wishlist-count', data.wishlistCount);
                    showToast('success', data.message);
                })
                .catch(() => { form.submit(); });
        });
    });

    /* ---------- AJAX add to cart ---------- */
    document.querySelectorAll('.js-cart-form').forEach((form) => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = form.querySelector('button[type=submit]');
            const original = btn ? btn.innerHTML : null;
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'fetch' },
                body: new FormData(form),
            })
                .then((r) => r.ok ? r.json() : Promise.reject())
                .then((data) => {
                    bumpBadge('.cart-count', data.cartCount);
                    showToast('success', data.message);
                    if (btn) {
                        btn.innerHTML = 'Added ✓';
                        setTimeout(() => { btn.innerHTML = original; }, 1500);
                    }
                })
                .catch(() => { form.submit(); });
        });
    });

    /* ---------- Quantity stepper (product details) ---------- */
    document.querySelectorAll('.qty-stepper').forEach((stepper) => {
        const display = stepper.querySelector('span');
        const input = document.querySelector(stepper.dataset.target);
        let qty = parseInt(display.textContent, 10) || 1;
        stepper.querySelectorAll('button').forEach((btn) => {
            btn.addEventListener('click', () => {
                qty = btn.dataset.action === 'inc' ? qty + 1 : Math.max(1, qty - 1);
                display.textContent = qty;
                if (input) input.value = qty;
            });
        });
    });

    /* ---------- Cart page qty auto-submit ---------- */
    document.querySelectorAll('.cart-qty-select').forEach((sel) => {
        sel.addEventListener('change', () => sel.closest('form').submit());
    });

    /* ---------- Checkout: payment method switch ---------- */
    const payMethods = document.querySelectorAll('.pay-method');
    const payPanels = document.querySelectorAll('.pay-panel');
    const payInput = document.getElementById('paymentMethodInput');
    payMethods.forEach((m) => {
        m.addEventListener('click', () => {
            payMethods.forEach((x) => x.classList.remove('active'));
            payPanels.forEach((x) => x.classList.remove('active'));
            m.classList.add('active');
            const target = document.getElementById('pay-' + m.dataset.method);
            target && target.classList.add('active');
            if (payInput) payInput.value = m.dataset.method;
        });
    });

    /* ---------- Checkout: live demo card preview ---------- */
    const cardNumberInput = document.getElementById('card_number');
    const cardNamePreview = document.getElementById('previewCardName');
    const cardNumberPreview = document.getElementById('previewCardNumber');
    const cardExpiryPreview = document.getElementById('previewCardExpiry');
    const cardNameInput = document.getElementById('card_name');
    const cardExpiryInput = document.getElementById('card_expiry');

    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', () => {
            let v = cardNumberInput.value.replace(/\D/g, '').slice(0, 16);
            cardNumberInput.value = v.replace(/(.{4})/g, '$1 ').trim();
            if (cardNumberPreview) {
                const padded = (v + '•'.repeat(16)).slice(0, 16);
                cardNumberPreview.textContent = padded.replace(/(.{4})/g, '$1 ').trim();
            }
        });
    }
    if (cardExpiryInput) {
        cardExpiryInput.addEventListener('input', () => {
            let v = cardExpiryInput.value.replace(/\D/g, '').slice(0, 4);
            if (v.length >= 3) v = v.slice(0, 2) + '/' + v.slice(2);
            cardExpiryInput.value = v;
            if (cardExpiryPreview) cardExpiryPreview.textContent = v || 'MM/YY';
        });
    }
    if (cardNameInput) {
        cardNameInput.addEventListener('input', () => {
            if (cardNamePreview) cardNamePreview.textContent = cardNameInput.value || 'YOUR NAME';
        });
    }

    /* ---------- Admin: image upload preview ---------- */
    const imageInput = document.getElementById('product_image');
    const imagePreview = document.getElementById('imagePreview');
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0];
            if (file) {
                imagePreview.src = URL.createObjectURL(file);
            }
        });
    }

    /* ---------- Auto-slugify hint on admin product name (visual only) ---------- */
    const searchToggle = document.getElementById('mobileSearchToggle');
    const mobileSearchBar = document.getElementById('mobileSearchBar');
    if (searchToggle && mobileSearchBar) {
        searchToggle.addEventListener('click', () => mobileSearchBar.classList.toggle('open'));
    }
})();
