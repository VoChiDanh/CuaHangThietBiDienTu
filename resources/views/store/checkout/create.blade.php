@extends('layouts.client')

@section('title', 'Thanh toán - D&D.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="page-heading">
        <div>
            <p class="eyebrow">HOÀN TẤT ĐẶT HÀNG</p>
            <h1>Thông tin thanh toán</h1>
        </div>
        <a class="text-link" href="{{ route('cart.index') }}">&larr; Quay lại giỏ hàng</a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200/90 p-4 sm:p-5 text-rose-800 text-xs sm:text-sm shadow-2xs" role="alert">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-base mt-0.5 shrink-0"></i>
                <div class="space-y-1">
                    <p class="font-bold text-rose-900">Vui lòng kiểm tra lại thông tin đặt hàng:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if (session('status'))
        <p class="flash-message" role="status">{{ session('status') }}</p>
    @endif

    @if (session('coupon_warning'))
        <p class="form-errors" role="alert">{{ session('coupon_warning') }}</p>
    @endif

    <div class="checkout-layout">
        <form id="checkout-form" class="card checkout-form" action="{{ route('checkout.store') }}" method="POST" novalidate>
            @csrf
            <section class="form-section">
                <h2>Địa chỉ nhận hàng</h2>
                @if ($addresses->isNotEmpty())
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700" for="address_id">Chọn từ sổ địa chỉ</label>
                        <select id="address_id" name="address_id" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm">
                            <option value="">Nhập địa chỉ khác</option>
                            @foreach ($addresses as $address)
                                <option value="{{ $address->id }}" @selected(old('address_id', $address->is_default ? $address->id : null) == $address->id)>
                                    {{ $address->receiver_name }} - {{ $address->receiver_phone }}: {{ $address->street_address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                                </option>
                            @endforeach
                        </select>
                        <p id="saved-address-hint" class="hidden text-xs text-blue-600 font-medium pt-1">
                            <i class="fa-solid fa-circle-check mr-1"></i>Đang sử dụng địa chỉ đã lưu ở trên. Chọn "Nhập địa chỉ khác" nếu muốn giao tới nơi mới.
                        </p>
                    </div>
                @endif

                <div class="address-fields space-y-4 pt-1" data-address-fields>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5" for="receiver_name">
                            Người nhận <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="receiver_name"
                            name="receiver_name"
                            value="{{ old('receiver_name', auth()->user()->name) }}"
                            placeholder="VD: Nguyễn Văn A"
                            autocomplete="name"
                            data-required-field="Vui lòng nhập họ tên người nhận."
                            class="{{ $errors->has('receiver_name') ? '!border-rose-400 !bg-rose-50/30' : '' }}"
                        >
                        <p class="field-error text-[11px] font-medium text-rose-600 mt-1 {{ $errors->has('receiver_name') ? '' : 'hidden' }}" data-error-for="receiver_name">
                            @error('receiver_name'){{ $message }}@enderror
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5" for="receiver_phone">
                            Số điện thoại <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="receiver_phone"
                            name="receiver_phone"
                            value="{{ old('receiver_phone', auth()->user()->phone) }}"
                            placeholder="VD: 0912345678"
                            inputmode="tel"
                            autocomplete="tel"
                            data-required-field="Vui lòng nhập số điện thoại nhận hàng."
                            class="{{ $errors->has('receiver_phone') ? '!border-rose-400 !bg-rose-50/30' : '' }}"
                        >
                        <p class="field-error text-[11px] font-medium text-rose-600 mt-1 {{ $errors->has('receiver_phone') ? '' : 'hidden' }}" data-error-for="receiver_phone">
                            @error('receiver_phone'){{ $message }}@enderror
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5" for="street_address">
                            Địa chỉ <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="street_address"
                            name="street_address"
                            value="{{ old('street_address') }}"
                            placeholder="VD: 123 Nguyễn Trãi (số nhà, tên đường)"
                            autocomplete="street-address"
                            data-required-field="Vui lòng nhập địa chỉ cụ thể (số nhà, tên đường)."
                            class="{{ $errors->has('street_address') ? '!border-rose-400 !bg-rose-50/30' : '' }}"
                        >
                        <p class="field-error text-[11px] font-medium text-rose-600 mt-1 {{ $errors->has('street_address') ? '' : 'hidden' }}" data-error-for="street_address">
                            @error('street_address'){{ $message }}@enderror
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5" for="ward">
                                Phường / xã <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="ward"
                                name="ward"
                                value="{{ old('ward') }}"
                                placeholder="VD: Phường Bến Thành"
                                data-required-field="Vui lòng nhập phường / xã."
                                class="{{ $errors->has('ward') ? '!border-rose-400 !bg-rose-50/30' : '' }}"
                            >
                            <p class="field-error text-[11px] font-medium text-rose-600 mt-1 {{ $errors->has('ward') ? '' : 'hidden' }}" data-error-for="ward">
                                @error('ward'){{ $message }}@enderror
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5" for="district">
                                Quận / huyện <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="district"
                                name="district"
                                value="{{ old('district') }}"
                                placeholder="VD: Quận 1"
                                data-required-field="Vui lòng nhập quận / huyện."
                                class="{{ $errors->has('district') ? '!border-rose-400 !bg-rose-50/30' : '' }}"
                            >
                            <p class="field-error text-[11px] font-medium text-rose-600 mt-1 {{ $errors->has('district') ? '' : 'hidden' }}" data-error-for="district">
                                @error('district'){{ $message }}@enderror
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5" for="province">
                            Tỉnh / thành phố <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="province"
                            name="province"
                            value="{{ old('province') }}"
                            placeholder="VD: TP. Hồ Chí Minh"
                            data-required-field="Vui lòng nhập tỉnh / thành phố."
                            class="{{ $errors->has('province') ? '!border-rose-400 !bg-rose-50/30' : '' }}"
                        >
                        <p class="field-error text-[11px] font-medium text-rose-600 mt-1 {{ $errors->has('province') ? '' : 'hidden' }}" data-error-for="province">
                            @error('province'){{ $message }}@enderror
                        </p>
                    </div>
                </div>
            </section>

            <section class="form-section">
                <h2>Đơn vị vận chuyển</h2>
                <label class="field-label" for="shipping_method">Chọn dịch vụ giao hàng</label>
                <select id="shipping_method" name="shipping_method" data-shipping-method required>
                    @foreach ($shippingMethods as $methodKey => $method)
                        <option
                            value="{{ $methodKey }}"
                            data-label="{{ $method['label'] }}"
                            data-fee="{{ $method['fee'] }}"
                            @selected($selectedShippingMethod === $methodKey)
                        >
                            {{ $method['label'] }} · {{ number_format($method['fee'], 0, ',', '.') }} ₫
                        </option>
                    @endforeach
                </select>
            </section>

            <section class="form-section">
                <h2>Phương thức thanh toán</h2>
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="cod" checked>
                    <span><strong>Thanh toán khi nhận hàng (COD)</strong><small>Thanh toán trực tiếp cho đơn vị vận chuyển.</small></span>
                </label>
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="vnpay" @disabled(! $vnpayEnabled)>
                    <span>
                        <strong>Thanh toán trực tuyến VNPay</strong>
                        <small>{{ $vnpayEnabled ? 'Thanh toán qua VNPay Sandbox.' : 'Cần cấu hình VNPAY_TMN_CODE và VNPAY_HASH_SECRET.' }}</small>
                    </span>
                </label>
            </section>

            <section class="form-section">
                <label class="block text-xs font-bold text-slate-700" for="note">Ghi chú đơn hàng</label>
                <textarea id="note" name="note" rows="3" placeholder="VD: Giao giờ hành chính, gọi trước khi giao...">{{ old('note') }}</textarea>
            </section>

            <button class="button button-primary button-full" type="submit">Đặt hàng</button>
        </form>

        <aside class="order-summary card">
            <h2>Đơn hàng</h2>
            @foreach ($items as $item)
                <div class="summary-product">
                    <span>{{ $item['variant']->product->name }} × {{ $item['quantity'] }}</span>
                    <strong>{{ number_format($item['lineTotal'], 0, ',', '.') }} ₫</strong>
                </div>
            @endforeach
            <div class="summary-line"><span>Tạm tính</span><strong>{{ number_format($subtotal, 0, ',', '.') }} ₫</strong></div>
            <div class="summary-line">
                <span>Giao hàng · <span data-shipping-method-label>{{ $shippingMethods[$selectedShippingMethod]['label'] }}</span></span>
                <strong data-shipping-fee>{{ number_format($shippingFee, 0, ',', '.') }} ₫</strong>
            </div>
            @if ($coupon)
                <div class="summary-line">
                    <span>Giảm giá · {{ $coupon->code }}</span>
                    <strong>-{{ number_format($coupon->discountAmount, 0, ',', '.') }} ₫</strong>
                </div>
            @endif
            <form class="coupon-form" action="{{ route('checkout.coupon.apply') }}" method="POST">
                @csrf
                <label class="field-label" for="coupon_code">Mã voucher ưu đãi</label>
                <div class="coupon-form-row">
                    <input
                        id="coupon_code"
                        name="coupon_code"
                        value="{{ old('coupon_code', $coupon?->code ?? '') }}"
                        autocomplete="off"
                        placeholder="Nhập mã voucher"
                        required
                    >
                    <button class="button button-secondary" type="submit">Áp dụng</button>
                </div>
                @if ($coupon)
                    <p class="coupon-applied" role="status">{{ $coupon->message }}</p>
                @endif
            </form>
            <div class="summary-total">
                <span>Tổng thanh toán</span>
                <strong
                    data-checkout-total
                    data-subtotal="{{ (int) round($subtotal) }}"
                    data-discount="{{ (int) round($discountAmount) }}"
                >
                    {{ number_format($subtotal - $discountAmount + $shippingFee, 0, ',', '.') }} ₫
                </strong>
            </div>
        </aside>
    </div>
</div>

@push('scripts')
<script>
(function initCheckoutValidation() {
    const form = document.getElementById('checkout-form');
    if (!form) return;

    const addressSelect = document.getElementById('address_id');
    const addressFieldsBox = form.querySelector('[data-address-fields]');
    const savedAddressHint = document.getElementById('saved-address-hint');
    const clientAlert = document.getElementById('checkout-client-alert');
    const clientAlertText = document.getElementById('checkout-client-alert-text');
    const requiredInputs = Array.from(form.querySelectorAll('[data-required-field]'));

    function setFieldError(input, message) {
        input.classList.add('!border-rose-400', '!bg-rose-50/30');
        const errorEl = form.querySelector(`[data-error-for="${input.name}"]`);
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }
    }

    function clearFieldError(input) {
        input.classList.remove('!border-rose-400', '!bg-rose-50/30');
        const errorEl = form.querySelector(`[data-error-for="${input.name}"]`);
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.classList.add('hidden');
        }
    }

    function syncSavedAddressState() {
        const usingSaved = Boolean(addressSelect && addressSelect.value.trim() !== '');
        if (addressFieldsBox) {
            addressFieldsBox.classList.toggle('opacity-50', usingSaved);
            addressFieldsBox.classList.toggle('pointer-events-none', usingSaved);
        }
        if (savedAddressHint) {
            savedAddressHint.classList.toggle('hidden', !usingSaved);
        }
        if (usingSaved) {
            requiredInputs.forEach(clearFieldError);
            if (clientAlert) clientAlert.classList.add('hidden');
        }
    }

    if (addressSelect) {
        addressSelect.addEventListener('change', syncSavedAddressState);
        syncSavedAddressState();
    }

    requiredInputs.forEach((input) => {
        input.addEventListener('input', () => {
            if (input.value.trim() !== '') {
                clearFieldError(input);
            }
            const stillHasErrors = requiredInputs.some((el) => !form.querySelector(`[data-error-for="${el.name}"]`)?.classList.contains('hidden'));
            if (!stillHasErrors && clientAlert) {
                clientAlert.classList.add('hidden');
            }
        });
    });

    form.addEventListener('submit', (event) => {
        const usingSaved = Boolean(addressSelect && addressSelect.value.trim() !== '');
        if (usingSaved) {
            return;
        }

        let firstInvalid = null;
        const missingMessages = [];

        requiredInputs.forEach((input) => {
            const value = input.value.trim();
            if (value === '') {
                const msg = input.dataset.requiredField || 'Vui lòng nhập thông tin này.';
                setFieldError(input, msg);
                missingMessages.push(msg);
                if (!firstInvalid) firstInvalid = input;
                return;
            }

            if (input.name === 'receiver_phone') {
                const digitsOnly = value.replace(/[\s.\-()+]/g, '');
                if (!/^\d{9,12}$/.test(digitsOnly)) {
                    const msg = 'Số điện thoại nhận hàng chưa hợp lệ (vui lòng nhập 9–12 chữ số).';
                    setFieldError(input, msg);
                    missingMessages.push(msg);
                    if (!firstInvalid) firstInvalid = input;
                    return;
                }
            }

            clearFieldError(input);
        });

        if (firstInvalid) {
            event.preventDefault();

            if (clientAlert && clientAlertText) {
                clientAlertText.textContent = missingMessages.join(' ');
                clientAlert.classList.remove('hidden');
            }

            if (window.showToast) {
                window.showToast('Vui lòng điền đầy đủ thông tin nhận hàng.', 'error');
            }

            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => firstInvalid.focus({ preventScroll: true }), 220);
        }
    });
})();
</script>
@endpush
@endsection
