@extends('layouts.app')

@section('title', $product->name . ' - ريتال ستور')

@section('content')
    <a href="{{ route('home') }}" class="back-link">رجوع للمتجر</a>

    <div class="product-detail">
        <div class="product-detail-media">
            <div class="photo-box" id="photo-box">
                <div class="small-photos" id="small-photos">
                    @foreach($product->images as $img)
                        <img class="small-photo {{ $loop->first ? 'active' : '' }}" src="{{ $img->image_url }}" alt="{{ $product->name }}">
                    @endforeach
                </div>
                <div class="big-photo-wrap">
                    <button type="button" class="photo-arrow photo-prev" id="photo-prev">‹</button>
                    @php
                        $firstPhoto = $product->primaryImage ? $product->primaryImage->image_url : ($product->images->first() ? $product->images->first()->image_url : asset('photos/photo-2.png'));
                    @endphp
                    <img class="big-photo" id="big-photo" src="{{ $firstPhoto }}" alt="{{ $product->name }}">
                    <button type="button" class="photo-arrow photo-next" id="photo-next">›</button>
                </div>
            </div>
        </div>

        <div class="product-detail-info">
            <h1 class="product-detail-name">{{ $product->name }}</h1>

            <span class="stock-badge">متاح للطلب</span>

            <p class="product-detail-price">
                @if($product->discount > 0)
                    <span style="text-decoration: line-through; color: #999; font-size: 0.8em; margin-left: 10px;">{{ number_format($product->price + $product->discount, 0) }}</span>
                @endif
                {{ number_format($product->price, 0) }} جنيه
            </p>

            @php
                $colors = $product->variants->whereNotNull('color_name')->unique('color_name');
                $defaultColor = $colors->first() ? $colors->first()->color_name : 'أسود';
                $sizes = $product->variants->whereNotNull('size')->unique('size');
                $defaultSize = $sizes->first() ? $sizes->first()->size : 'L';
            @endphp

            @if($colors->count() > 0)
                <p class="option-label">
                    اللون:
                    <span id="chosen-color">{{ $defaultColor }}</span>
                </p>

                <div class="color-row" id="color-row">
                    @foreach($colors as $variant)
                        <button type="button" 
                                class="color-dot {{ $loop->first ? 'selected' : '' }}" 
                                data-color="{{ $variant->color_name }}" 
                                style="background-color: {{ $variant->color_code ?: '#2b2b2b' }};" 
                                aria-label="{{ $variant->color_name }}">
                        </button>
                    @endforeach
                </div>
            @endif

            @if($sizes->count() > 0)
                <p class="option-label">
                    المقاس:
                    <span id="chosen-size">{{ $defaultSize }}</span>
                </p>

                <div class="size-row" id="size-row">
                    @foreach($sizes as $sVariant)
                        <button type="button" 
                                class="size-btn {{ $loop->first ? 'selected' : '' }}" 
                                data-size="{{ $sVariant->size }}">
                            {{ $sVariant->size }}
                        </button>
                    @endforeach
                </div>
            @endif

            <p class="option-label" style="margin-top: 15px;">
                الكمية:
                <span id="max-qty-label" style="font-size: 0.8em; color: #666; font-weight: normal;">(المتاح: <span id="available-qty">1</span>)</span>
            </p>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <button type="button" id="qty-minus" style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ccc; background: white; font-size: 20px; cursor: pointer;">-</button>
                <input type="number" id="qty-input" value="1" min="1" max="1" readonly style="width: 60px; height: 40px; text-align: center; border: 1px solid #ccc; border-radius: 8px; font-size: 18px; font-family: 'Cairo', sans-serif;">
                <button type="button" id="qty-plus" style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ccc; background: white; font-size: 20px; cursor: pointer;">+</button>
            </div>

            <form id="add-to-cart-form" method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="color" id="form-add-color" value="{{ $defaultColor }}">
                <input type="hidden" name="size" id="form-add-size" value="{{ $defaultSize }}">
                <input type="hidden" name="quantity" id="form-add-qty" value="1">

                <button type="submit" id="add-to-cart-btn" class="whatsapp-order-btn" style="background-color: #d15c72; color: white; width: 100%;">
                    أضف في السلة 🛒
                </button>
            </form>
        </div>
    </div>

    <!-- نافذة إتمام الطلب (Checkout Modal) -->
    <div class="checkout-overlay" id="checkout-overlay">
        <div class="checkout-box">
            <button type="button" class="checkout-close" id="checkout-close">×</button>
            <div class="checkout-summary">
                <div class="summary-product">
                    <img id="checkout-product-img" src="{{ asset($firstPhoto) }}" alt="">
                    <div class="summary-product-info">
                        <p id="checkout-product-name" class="summary-product-name">{{ $product->name }}</p>
                        <p id="checkout-product-options" class="summary-product-options"></p>
                    </div>
                    <p id="checkout-product-price" class="summary-product-price">{{ number_format($product->price, 0) }} جنيه</p>
                </div>
                <div class="summary-line">
                    <span>المجموع الفرعي</span>
                    <span id="checkout-subtotal">{{ number_format($product->price, 0) }} جنيه</span>
                </div>
                <div class="summary-line">
                    <span>الشحن</span>
                    <span class="free-shipping">مجاني</span>
                </div>
                <div class="summary-line summary-total">
                    <span>الإجمالي</span>
                    <span id="checkout-total">{{ number_format($product->price, 0) }} جنيه</span>
                </div>
            </div>

            <form id="checkout-form" method="POST" action="{{ route('order.submit') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="color" id="form-order-color" value="{{ $defaultColor }}">
                <input type="hidden" name="size" id="form-order-size" value="{{ $defaultSize }}">

                <h3 class="checkout-section-title">بيانات التواصل</h3>
                <input type="tel" name="phone" id="checkout-phone" class="input" placeholder="رقم الموبايل" required>
                
                <h3 class="checkout-section-title">عنوان التوصيل</h3>
                <input type="text" name="name" id="checkout-name" class="input" placeholder="الاسم بالكامل" required>
                <input type="text" name="address" id="checkout-address" class="input" placeholder="العنوان بالتفصيل" required>
                <input type="text" name="city" id="checkout-city" class="input" placeholder="المحافظة / المدينة" required>
                
                <h3 class="checkout-section-title">طريقة الدفع</h3>
                <div class="payment-option selected">
                    <span>الدفع عند الاستلام (كاش)</span>
                    <span class="payment-check">✓</span>
                </div>
                
                <p id="checkout-error" class="login-error"></p>
                <button type="submit" class="confirm-order-btn" id="checkout-submit-btn">تأكيد الطلب</button>
            </form>
        </div>
    </div>

    <!-- نافذة نجاح الطلب (Success Modal) -->
    <div class="checkout-overlay" id="success-overlay">
        <div class="checkout-box success-box">
            <div class="success-icon">✓</div>
            <h2 class="success-title">تم تأكيد الطلب بنجاح</h2>
            <p class="success-text">هيتم التواصل معاكِ قريبًا لتأكيد التفاصيل والتوصيل.</p>
            <p class="success-order-id" id="success-order-summary"></p>
            <button type="button" class="confirm-order-btn" id="success-close-btn">تمام</button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // ربط إرسال نموذج الدفع بالباك إند مباشرة مع إبقاء الأنيميشن والتجربة الأصلية 100%
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('checkout-form');
        var colorTextEl = document.getElementById('chosen-color');
        var sizeTextEl = document.getElementById('chosen-size');
        var formColor = document.getElementById('form-order-color');
        var formSize = document.getElementById('form-order-size');

        var variants = @json($product->variants);
        var qtyInput = document.getElementById('qty-input');
        var qtyMinus = document.getElementById('qty-minus');
        var qtyPlus = document.getElementById('qty-plus');
        var availableQtyEl = document.getElementById('available-qty');
        var maxQty = 1;

        function updateStockLimit() {
            var selectedColor = colorTextEl ? colorTextEl.textContent.trim() : null;
            var selectedSize = sizeTextEl ? sizeTextEl.textContent.trim() : null;
            
            var variant = variants.find(function(v) {
                return (!selectedColor || v.color_name === selectedColor) && 
                       (!selectedSize || v.size === selectedSize);
            });

            if (variant && variant.stock_quantity > 0) {
                maxQty = variant.stock_quantity;
            } else {
                maxQty = 1; // default fallback
            }

            if (parseInt(qtyInput.value) > maxQty) {
                qtyInput.value = maxQty;
            }
            availableQtyEl.textContent = maxQty;
            
            var formAddQty = document.getElementById('form-add-qty');
            if (formAddQty) formAddQty.value = qtyInput.value;
        }

        updateStockLimit();

        if (colorTextEl) {
            // update max stock when color changes
            var colorDots = document.querySelectorAll('.color-dot');
            colorDots.forEach(function(dot) {
                dot.addEventListener('click', function() {
                    setTimeout(updateStockLimit, 50); // wait for main.js to update text
                });
            });
        }

        if (sizeTextEl) {
             // update max stock when size changes
             var sizeBtns = document.querySelectorAll('.size-btn');
             sizeBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    setTimeout(updateStockLimit, 50);
                });
             });
        }

        if (qtyMinus && qtyPlus) {
            qtyMinus.onclick = function() {
                var current = parseInt(qtyInput.value);
                if (current > 1) {
                    qtyInput.value = current - 1;
                    document.getElementById('form-add-qty').value = qtyInput.value;
                }
            };
            qtyPlus.onclick = function() {
                var current = parseInt(qtyInput.value);
                if (current < maxQty) {
                    qtyInput.value = current + 1;
                    document.getElementById('form-add-qty').value = qtyInput.value;
                }
            };
        }

        // تحديث الصور الصغيرة عند النقر
        var smallPhotos = document.querySelectorAll('.small-photo');
        var bigPhoto = document.getElementById('big-photo');
        var photoPrevBtn = document.getElementById('photo-prev');
        var photoNextBtn = document.getElementById('photo-next');

        function updateBigPhoto(index) {
            if(smallPhotos.length === 0) return;
            if(index >= smallPhotos.length) index = 0;
            if(index < 0) index = smallPhotos.length - 1;
            
            smallPhotos.forEach(function(s) { s.classList.remove('active'); });
            smallPhotos[index].classList.add('active');
            if (bigPhoto) bigPhoto.src = smallPhotos[index].src;
            if (bigPhoto) bigPhoto.setAttribute('data-current-index', index);
        }

        if (smallPhotos.length > 0) {
            var activeIdx = 0;
            smallPhotos.forEach(function(img, idx) {
                if(img.classList.contains('active')) activeIdx = idx;
                img.onclick = function() {
                    updateBigPhoto(idx);
                };
            });
            if (bigPhoto) bigPhoto.setAttribute('data-current-index', activeIdx);

            if (photoNextBtn) {
                photoNextBtn.onclick = function() {
                    var curr = parseInt(bigPhoto.getAttribute('data-current-index') || 0);
                    updateBigPhoto(curr + 1);
                };
            }
            if (photoPrevBtn) {
                photoPrevBtn.onclick = function() {
                    var curr = parseInt(bigPhoto.getAttribute('data-current-index') || 0);
                    updateBigPhoto(curr - 1);
                };
            }
        }

        var addForm = document.getElementById('add-to-cart-form');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                if (colorTextEl) document.getElementById('form-add-color').value = colorTextEl.textContent.trim();
                if (sizeTextEl) document.getElementById('form-add-size').value = sizeTextEl.textContent.trim();
                
                var btn = document.getElementById('add-to-cart-btn');
                btn.disabled = true;
                btn.textContent = 'جاري الإضافة...';
            });
        }
    });
</script>
@endpush
