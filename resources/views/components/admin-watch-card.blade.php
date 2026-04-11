<div class="product-card" data-name="{{$name}}" data-type="{{$type}}">

    <div class="card-content">
        <div class="card-left">
            <h3>{{ html_entity_decode($name) }}</h3>
            <p><strong>Price:</strong> ${{ $price }}</p>
            <p><strong>Type:</strong> {{ html_entity_decode($type) }}</p>
            <p>
                <strong>Colors:</strong>
                @php
                $colorList = [
                $color ?? null,
                $color1 ?? null,
                $color2 ?? null,
                $color3 ?? null,
                $color4 ?? null,
                $color5 ?? null,
                $color6 ?? null,
                ];
                @endphp

                @foreach ($colorList as $col)
                @php
                $col = trim($col);
                @endphp
                @if (!empty($col) && str_starts_with($col, '#'))
                <span class="color-dot" style="background-color: {{ $col }};" title="{{ $col }}"></span>
                @endif
                @endforeach
            </p>

        </div>
        <div class="card-right">
            <div class="big-price">{{ $cost }}</div>
        </div>
    </div>

    <div class="btn-group">
        <button class="btn-edit" onclick="window.location.href='/admin/editWatch/{{$id}}'">Edit</button>
        <button class="btn-delete" onclick="deleteProduct({{ $id }}, this, '{{ $name }}','watch')">Delete</button>
    </div>
</div>