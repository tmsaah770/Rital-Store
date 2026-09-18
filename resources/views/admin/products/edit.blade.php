@extends('layouts.admin')

@section('title', 'تعديل منتج: ' . $product->name . ' - ريتال ستور')

@push('styles')
<style>
    #product-category {
        font-family: 'Cairo', sans-serif !important;
        font-size: 12.5px !important;
        font-weight: 500 !important;
        height: 38px !important;
        padding: 5px 16px !important;
        padding-left: 36px !important;
        border-radius: 25px !important;
        cursor: pointer !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        background-color: #FFF9F9 !important;
        border: 1px solid var(--card-border, #e2d5d8) !important;
        background-image: url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%3E%3Cpath%20fill%3D%22%235C1A2E%22%20d%3D%22M1.41%200L6%204.58%2010.59%200%2012%201.41l-6%206-6-6z%22%2F%3E%3C%2Fsvg%3E') !important;
        background-repeat: no-repeat !important;
        background-position: left 14px center !important;
        background-size: 10px 6px !important;
        box-sizing: border-box !important;
        width: 100% !important;
        color: #2b2b2b !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
    }
    #product-category:focus {
        outline: none !important;
        border-color: #5C1A2E !important;
        box-shadow: 0 0 0 2px rgba(92, 26, 46, 0.12) !important;
    }
    #product-category option {
        font-size: 12px !important;
        font-family: 'Cairo', sans-serif !important;
        font-weight: 500 !important;
        padding: 5px 10px !important;
        color: #2b2b2b !important;
        background: #ffffff !important;
    }
    .input {
        font-family: 'Cairo', sans-serif !important;
        font-size: 13.5px !important;
        height: 40px !important;
        border-radius: 25px !important;
        padding: 7px 18px !important;
        background-color: #FFF9F9 !important;
        border: 1px solid var(--card-border, #e2d5d8) !important;
        box-sizing: border-box !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
    }
    .input:focus {
        outline: none !important;
        border-color: #5C1A2E !important;
        box-shadow: 0 0 0 2px rgba(92, 26, 46, 0.12) !important;
    }
    .color-picker-circle {
        width: 28px !important;
        height: 28px !important;
        border-radius: 50% !important;
        border: 2px dashed #5C1A2E !important;
        background: #FFF9F9 !important;
        box-shadow: 0 0 0 1px var(--card-border, #e2d5d8) !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        position: relative !important;
        box-sizing: border-box !important;
        padding: 0 !important;
        transition: transform 0.15s ease, border-color 0.2s ease, box-shadow 0.2s ease !important;
    }
    .color-picker-circle:hover {
        transform: scale(1.1) !important;
        border-color: #8B2635 !important;
        box-shadow: 0 0 0 2px rgba(92, 26, 46, 0.2) !important;
    }
    .color-picker-circle .plus-icon {
        font-size: 18px !important;
        font-weight: bold !important;
        color: #5C1A2E !important;
        line-height: 1 !important;
        pointer-events: none !important;
        user-select: none !important;
        margin-top: -2px !important;
    }
    .color-picker-circle input[type="color"] {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        opacity: 0 !important;
        cursor: pointer !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
    }
    .form-close-btn {
        position: absolute !important;
        top: 16px !important;
        right: 16px !important;
        width: 40px !important;
        height: 40px !important;
        border-radius: 50% !important;
        background-color: #dc2626 !important;
        color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.35) !important;
        transition: transform 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease !important;
        z-index: 10 !important;
    }
    .form-close-btn:hover {
        background-color: #b91c1c !important;
        transform: scale(1.08) !important;
        box-shadow: 0 4px 12px rgba(185, 28, 28, 0.45) !important;
    }

    /* Dropzone Styles */
    .image-dropzone-container {
        width: 220px !important;
        height: 220px !important;
        background-color: #131622 !important;
        border: 2px dashed #374151 !important;
        border-radius: 20px !important;
        position: relative !important;
        cursor: pointer !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        transition: all 0.25s ease !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 10px 0 20px 0 !important;
    }
    .image-dropzone-container:hover,
    .image-dropzone-container.drag-over {
        border-color: #6366f1 !important;
        background-color: #181d2d !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35) !important;
    }
    .image-dropzone-empty {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
        height: 100% !important;
        pointer-events: none !important;
    }
    .image-dropzone-empty svg {
        stroke: #48536f !important;
        transition: stroke 0.25s ease !important;
    }
    .image-dropzone-container:hover .image-dropzone-empty svg {
        stroke: #818cf8 !important;
    }
    .image-dropzone-empty .upload-label {
        font-family: 'Cairo', sans-serif !important;
        font-size: 13.5px !important;
        font-weight: 600 !important;
        color: #48536f !important;
        margin-top: 10px !important;
        transition: color 0.25s ease !important;
    }
    .image-dropzone-container:hover .image-dropzone-empty .upload-label {
        color: #818cf8 !important;
    }
    .image-dropzone-grid {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
        padding: 10px !important;
        width: 100% !important;
        height: 100% !important;
        box-sizing: border-box !important;
        overflow-y: auto !important;
        align-content: start !important;
    }
    .image-dropzone-grid::-webkit-scrollbar {
        width: 4px !important;
    }
    .image-dropzone-grid::-webkit-scrollbar-thumb {
        background: #374151 !important;
        border-radius: 4px !important;
    }
    .thumb-cell {
        position: relative !important;
        width: 100% !important;
        aspect-ratio: 1 !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        background-color: #1e2433 !important;
    }
    .thumb-cell img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        display: block !important;
    }
    .thumb-cell .remove-btn {
        position: absolute !important;
        top: 4px !important;
        right: 4px !important;
        width: 20px !important;
        height: 20px !important;
        border-radius: 50% !important;
        background: rgba(220, 38, 38, 0.9) !important;
        color: #ffffff !important;
        border: none !important;
        font-size: 11px !important;
        font-weight: bold !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        opacity: 0 !important;
        transition: opacity 0.2s ease, transform 0.15s ease !important;
        z-index: 5 !important;
    }
    .thumb-cell:hover .remove-btn {
        opacity: 1 !important;
    }
    .thumb-cell .remove-btn:hover {
        transform: scale(1.15) !important;
        background: #ef4444 !important;
    }
    .add-more-cell {
        width: 100% !important;
        aspect-ratio: 1 !important;
        border-radius: 10px !important;
        border: 1.5px dashed #374151 !important;
        background: rgba(255, 255, 255, 0.03) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #6b7280 !important;
        font-size: 22px !important;
        font-weight: bold !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }
    .add-more-cell:hover {
        border-color: #818cf8 !important;
        color: #818cf8 !important;
        background: rgba(129, 140, 248, 0.08) !important;
        transform: scale(1.04) !important;
    }
</style>
@endpush

@section('content')
    <section class="dashboard">

        <!-- قسم إدارة وتعديل المنتج -->
        <section class="products-manager" style="margin-top: 10px; position: relative;">
            <a href="{{ route('admin.products.index') }}" 
               class="form-close-btn" 
               title="الرجوع لصفحة إدارة المنتجات"
               aria-label="الرجوع لصفحة إدارة المنتجات">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>

            <h1 class="dashboard-title" style="margin: 0;">تعديل المنتج</h1>
            <h2 style="text-align: center;">بيانات المنتج والمخزون (كود المنتج: #{{ $product->id }})</h2>

            @if(session('success'))
                <p style="color: #27ae60; font-weight: bold; margin-bottom: 10px; text-align: center;">{{ session('success') }}</p>
            @endif

            <form id="product-form" method="POST" action="{{ route('admin.products.save') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px; align-items: center; justify-content: center;">
                @csrf
                <input type="hidden" name="id" id="edit-product-id" value="{{ $product->id }}">

                <p class="option-label">صور المنتج</p>
                <p class="dash-hint">اختاري صورة أو أكثر للمنتج .</p>
                <input id="photo-input" name="photos[]" type="file" accept="image/*" multiple style="display: none;">

                <div id="image-dropzone" class="image-dropzone-container" title="اضغطي لرفع الصور أو اسحبي الصور إلى هنا">
                    {{-- الحالة الفارغة --}}
                    <div id="dropzone-empty-state" class="image-dropzone-empty" style="{{ $product->images->count() > 0 ? 'display: none !important;' : '' }}">
                        <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="4" ry="4"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        <span class="upload-label">Upload Images</span>
                    </div>

                    {{-- شبكة الصور المرفوعة (صور حالية + صور جديدة مضافة) --}}
                    <div id="dropzone-grid-state" class="image-dropzone-grid" style="{{ $product->images->count() > 0 ? '' : 'display: none !important;' }}">
                        @foreach($product->images as $img)
                            <div class="thumb-cell existing-thumb" data-image-id="{{ $img->id }}" id="existing-img-{{ $img->id }}">
                                <img src="{{ $img->image_url }}" alt="صورة المنتج">
                                <button type="button" class="remove-btn" title="حذف هذه الصورة" onclick="deleteExistingImage({{ $img->id }}, event)">✕</button>
                            </div>
                        @endforeach
                        <div class="add-more-cell" title="إضافة المزيد من الصور">+</div>
                    </div>
                </div>

                <p class="option-label">اسم المنتج</p>
                <input id="product-name" name="name" class="input" type="text" placeholder="اسم المنتج" value="{{ $product->name }}" required>

                <p class="option-label">قسم / تصنيف المنتج</p>
                <select id="product-category" name="category_id" class="input">
                    <option value="">-- اختاري قسم المنتج --</option>
                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    @endif
                </select>

                @php
                    $savedColors = $product->variants
                        ? $product->variants->pluck('color_name')->unique()->filter()->values()->toArray()
                        : [];
                    $savedSizes = $product->variants
                        ? $product->variants->pluck('size')->unique()->filter()->values()->toArray()
                        : [];
                    $standardColors = [
                        ['name' => 'أسود', 'code' => '#2b2b2b'],
                        ['name' => 'رمادي', 'code' => '#8a7d74'],
                        ['name' => 'بيج', 'code' => '#e8dcc8'],
                        ['name' => 'كحلي', 'code' => '#2c3e50'],
                        ['name' => 'بني', 'code' => '#a67c52'],
                        ['name' => 'ابيض', 'code' => '#ffffff', 'border' => 'border: 1px solid #ddd;'],
                    ];
                    $standardNames = array_column($standardColors, 'name');
                    $customSavedColors = array_diff($savedColors, $standardNames);
                @endphp

                <p class="option-label">الألوان المتاحة</p>
                <p class="dash-hint">اضغطي على الدائرة لتفعيل اللون للمنتج (غير مفعلة افتراضياً). يمكنك أيضاً إضافة ألوان مخصصة جديدة عبر زر (+).</p>
                <div class="color-row" id="dash-color-row" style="align-items: center; display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 18px;">
                    @foreach($standardColors as $sc)
                        <button type="button" 
                                class="color-dot {{ in_array($sc['name'], $savedColors) ? 'selected' : '' }}" 
                                data-color="{{ $sc['name'] }}" 
                                style="background-color: {{ $sc['code'] }}; {{ $sc['border'] ?? '' }}" 
                                aria-label="{{ $sc['name'] }}" 
                                title="{{ $sc['name'] }}">
                        </button>
                    @endforeach

                    {{-- ألوان مخصصة محفوظة مسبقاً لهذا المنتج --}}
                    @foreach($customSavedColors as $cc)
                        <button type="button" 
                                class="color-dot selected" 
                                data-color="{{ $cc }}" 
                                style="background-color: {{ $cc }};" 
                                aria-label="{{ $cc }}" 
                                title="{{ $cc }}">
                        </button>
                    @endforeach

                    {{-- زر اختيار وإضافة لون مخصص جديد --}}
                    <div id="color-picker-btn" class="color-picker-circle" title="اختيار وإضافة لون مخصص جديد (+)">
                        <span class="plus-icon">+</span>
                        <input type="color" id="custom-color-input" value="#9333ea" aria-label="اختيار لون مخصص">
                    </div>
                </div>
                <input type="hidden" name="colors" id="available-colors" value="{{ implode(',', $savedColors) }}">

                <p class="option-label">المقاسات المتاحة</p>
                <p class="dash-hint">اضغطي على المقاس لتفعيله (المقاس المظلل فقط هو المتاح).</p>
                <div class="size-row" id="dash-size-row">
                    @php $allSizes = ['S', 'M', 'L', 'XL', '2XL']; @endphp
                    @foreach($allSizes as $sz)
                        <button type="button" 
                                class="size-btn {{ in_array($sz, $savedSizes) ? 'selected' : '' }}" 
                                data-size="{{ $sz }}">
                            {{ $sz }}
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="sizes" id="available-sizes" value="{{ implode(',', $savedSizes) }}">

                <p class="option-label">السعر</p>
                <input id="product-price" name="price" class="input" type="number" step="0.01" placeholder="مثال: 450" value="{{ $product->price }}" required>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-top: 10px; ">
                    <div>
                        <p class="option-label" style="text-align: center;">الخصم</p>
                        <input id="product-discount" name="discount" class="input" type="number" step="0.01" min="0" placeholder="مثال: 50 (جنيه)" value="{{ ($product->discount && $product->discount > 0) ? $product->discount : '' }}">
                    </div>
                    <div>
                        <p class="option-label" style="text-align: center;">الكمية</p>
                        <input id="product-quantity" name="quantity" class="input" type="number" min="0" placeholder="مثال: 50" value="{{ $product->quantity }}">
                    </div>
                </div>
                <br>
                <p id="save-message" class="dash-hint"></p>
                
                <div class="buttons-container">
                    <button type="submit" id="save-btn" style="width: 200px;">حفظ التعديلات</button>
                </div>
            </form>

        </section>
    </section>
@endsection

@push('scripts')
<script>
// حذف صورة حالية مسجلة مسبقاً في السيرفر
function deleteExistingImage(imageId, event) {
    event.stopPropagation();
    if (!confirm('هل تريد حذف هذه الصورة نهائياً؟')) return;

    fetch(`{{ url('/admin/products/images') }}/${imageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const el = document.getElementById(`existing-img-${imageId}`);
            if (el) el.remove();
            checkDropzoneEmptyState();
        } else {
            alert('تعذر حذف الصورة، يرجى المحاولة مرة أخرى.');
        }
    })
    .catch(() => {
        alert('حدث خطأ أثناء حذف الصورة.');
    });
}

function checkDropzoneEmptyState() {
    const gridState = document.getElementById('dropzone-grid-state');
    const emptyState = document.getElementById('dropzone-empty-state');
    const existingThumbs = gridState.querySelectorAll('.existing-thumb');
    const newThumbs = gridState.querySelectorAll('.new-thumb');

    if (existingThumbs.length === 0 && newThumbs.length === 0) {
        emptyState.style.removeProperty('display');
        gridState.style.setProperty('display', 'none', 'important');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photo-input');
    const dropzone = document.getElementById('image-dropzone');
    const emptyState = document.getElementById('dropzone-empty-state');
    const gridState = document.getElementById('dropzone-grid-state');

    let dt = new DataTransfer();

    function renderNewThumbnails() {
        // إزالة الصور الجديدة السابقة
        gridState.querySelectorAll('.new-thumb').forEach(el => el.remove());
        const addMore = gridState.querySelector('.add-more-cell');

        if (dt.files.length > 0) {
            emptyState.style.setProperty('display', 'none', 'important');
            gridState.style.setProperty('display', 'grid', 'important');

            Array.from(dt.files).forEach((file, index) => {
                const cell = document.createElement('div');
                cell.className = 'thumb-cell new-thumb';

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = file.name;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'remove-btn';
                removeBtn.innerHTML = '✕';
                removeBtn.title = 'حذف الصورة';
                removeBtn.onclick = function(e) {
                    e.stopPropagation();
                    removeNewFile(index);
                };

                cell.appendChild(img);
                cell.appendChild(removeBtn);
                if (addMore) {
                    gridState.insertBefore(cell, addMore);
                } else {
                    gridState.appendChild(cell);
                }
            });
        }
        checkDropzoneEmptyState();
    }

    function removeNewFile(index) {
        const newDt = new DataTransfer();
        Array.from(dt.files).forEach((file, i) => {
            if (i !== index) newDt.items.add(file);
        });
        dt = newDt;
        photoInput.files = dt.files;
        renderNewThumbnails();
    }

    function handleFiles(files) {
        if (!files || files.length === 0) return;
        for (let i = 0; i < files.length; i++) {
            const f = files[i];
            if (f.type && f.type.startsWith('image/')) {
                dt.items.add(f);
            }
        }
        photoInput.files = dt.files;
        renderNewThumbnails();
    }

    dropzone.addEventListener('click', function(e) {
        if (!e.target.closest('.remove-btn') && !e.target.closest('.add-more-cell')) {
            photoInput.click();
        }
    });

    const addMoreBtn = gridState.querySelector('.add-more-cell');
    if (addMoreBtn) {
        addMoreBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            photoInput.click();
        });
    }

    photoInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            handleFiles(this.files);
        }
    });

    // السحب والإفلات
    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.add('drag-over');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropzone.classList.remove('drag-over');
        });
    });

    dropzone.addEventListener('drop', function(e) {
        if (e.dataTransfer && e.dataTransfer.files) {
            handleFiles(e.dataTransfer.files);
        }
    });

    // إدارة الألوان
    const colorRow = document.getElementById('dash-color-row');
    const colorsInput = document.getElementById('available-colors');
    const colorPickerBtn = document.getElementById('color-picker-btn');
    const customColorInput = document.getElementById('custom-color-input');

    function updateColorsInput() {
        const selected = Array.from(colorRow.querySelectorAll('.color-dot.selected'))
            .map(dot => dot.getAttribute('data-color'));
        colorsInput.value = selected.join(',');
    }

    colorRow.addEventListener('click', function(e) {
        const dot = e.target.closest('.color-dot');
        if (!dot) return;
        dot.classList.toggle('selected');
        updateColorsInput();
    });

    if (customColorInput) {
        customColorInput.addEventListener('change', function() {
            const newColor = this.value;
            if (!newColor) return;

            let existingDot = colorRow.querySelector(`.color-dot[data-color="${newColor}"]`);
            if (existingDot) {
                existingDot.classList.add('selected');
            } else {
                const newDot = document.createElement('button');
                newDot.type = 'button';
                newDot.className = 'color-dot selected';
                newDot.setAttribute('data-color', newColor);
                newDot.style.backgroundColor = newColor;
                newDot.title = newColor;
                colorRow.insertBefore(newDot, colorPickerBtn);
            }
            updateColorsInput();
        });
    }

    // إدارة المقاسات
    const sizeRow = document.getElementById('dash-size-row');
    const sizesInput = document.getElementById('available-sizes');

    function updateSizesInput() {
        const selected = Array.from(sizeRow.querySelectorAll('.size-btn.selected'))
            .map(btn => btn.getAttribute('data-size'));
        sizesInput.value = selected.join(',');
    }

    sizeRow.addEventListener('click', function(e) {
        const btn = e.target.closest('.size-btn');
        if (!btn) return;
        btn.classList.toggle('selected');
        updateSizesInput();
    });
});
</script>
@endpush
