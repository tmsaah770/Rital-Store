@php
    $primaryImg = $product->primaryImage ? $product->primaryImage->image_url : ($product->images->first() ? $product->images->first()->image_url : asset('photos/photo-2.png'));
@endphp
<div class="product-card">
    <a href="{{ route('product.show', $product->id) }}" class="product-link">
        <img src="{{ $primaryImg }}" alt="{{ $product->name }}">
        <p class="product-name">{{ $product->name }}</p>
        <p class="product-price">
            @if($product->discount > 0)
                <span style="text-decoration: line-through; color: #999; font-size: 0.9em;">{{ number_format($product->price + $product->discount, 0) }}</span> 
            @endif
            {{ number_format($product->price, 0) }} جنيه
        </p>
    </a>
    <a href="{{ route('product.show', $product->id) }}" class="order-btn">اطلب الآن</a>
</div>
