import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const currency = new Intl.NumberFormat('vi-VN', {
    maximumFractionDigits: 0,
});

document.addEventListener('submit', async (event) => {
    const form = event.target.closest('[data-ajax-cart], [data-cart-add]');

    if (!form) {
        return;
    }

    event.preventDefault();

    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    const formData = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': token,
            },
            body: formData,
        });
        const result = await response.json();

        if (!response.ok) {
            const message = Object.values(result.errors ?? {}).flat()[0] ?? result.message ?? 'Không thể cập nhật giỏ hàng.';
            showCartMessage(message, true);
            return;
        }

        document.querySelectorAll('[data-cart-count]').forEach((element) => {
            element.textContent = result.cart_count;
        });

        if (form.matches('[data-cart-add]')) {
            showCartMessage(result.message, false);
            return;
        }

        const row = form.closest('[data-cart-row]');

        if (form.dataset.cartAction === 'remove') {
            row?.remove();
        } else if (row) {
            const quantity = Number(form.querySelector('[name="quantity"]').value);
            row.dataset.quantity = quantity;
            row.querySelector('.cart-line-total').textContent = `${currency.format(Number(result.line_totals[row.dataset.variantId]))} ₫`;
        }

        updateCartSummary(result);
        showCartMessage(result.message, false);

        if (document.querySelectorAll('[data-cart-row]').length === 0) {
            window.location.reload();
        }
    } catch {
        showCartMessage('Không thể kết nối để cập nhật giỏ hàng. Vui lòng thử lại.', true);
    }
});

document.addEventListener('change', (event) => {
    const shippingSelect = event.target.closest('[data-shipping-method]');

    if (!shippingSelect) {
        return;
    }

    const selectedOption = shippingSelect.selectedOptions[0];
    const feeDisplay = document.querySelector('[data-shipping-fee]');
    const methodDisplay = document.querySelector('[data-shipping-method-label]');
    const totalDisplay = document.querySelector('[data-checkout-total]');

    if (!selectedOption || !feeDisplay || !methodDisplay || !totalDisplay) {
        return;
    }

    const shippingFee = Number(selectedOption.dataset.fee);
    const subtotal = Number(totalDisplay.dataset.subtotal);
    const discount = Number(totalDisplay.dataset.discount || 0);

    if (!Number.isFinite(shippingFee) || !Number.isFinite(subtotal) || !Number.isFinite(discount)) {
        return;
    }

    feeDisplay.textContent = `${currency.format(shippingFee)} ₫`;
    methodDisplay.textContent = selectedOption.dataset.label;
    totalDisplay.textContent = `${currency.format(subtotal - discount + shippingFee)} ₫`;
});

function updateCartSummary(result) {
    const rows = [...document.querySelectorAll('[data-cart-row]')];

    document.querySelectorAll('[data-cart-count-total]').forEach((element) => {
        element.textContent = result.cart_count;
    });
    document.querySelectorAll('[data-cart-subtotal], [data-cart-total]').forEach((element) => {
        element.textContent = `${currency.format(Number(result.subtotal))} ₫`;
    });
}

function showCartMessage(message, isError) {
    const element = document.querySelector('[data-cart-message]');

    if (element) {
        element.hidden = false;
        element.textContent = message;
        element.classList.toggle('form-errors', isError);
        return;
    }

    const notification = document.createElement('div');
    notification.className = isError ? 'form-errors flash-message' : 'flash-message';
    notification.setAttribute('role', isError ? 'alert' : 'status');
    notification.textContent = message;
    document.body.append(notification);
    window.setTimeout(() => notification.remove(), 3500);
}
