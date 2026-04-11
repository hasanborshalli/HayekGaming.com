<div class="product-description">
    @if ($description)
    <h2>Description</h2>
    <div class="small-description" id="descriptionText">
        <p>
            {!!$description !!}
        </p>
    </div>
    <button class="toggle-btn" id="toggleDescriptionBtn">View More</button>
    @endif
    @if($features)
    <div class="product-benefits">
        <h3>List Of Features:</h3>
        <ul>
            @foreach ($features as $feature)
            <li>{{ html_entity_decode($feature) }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($boxContents)
    <div class="product-sides">
        <h3>What's in the box:</h3>
        <ul>
            @foreach ($boxContents as $boxContent)
            <li>{{ html_entity_decode($boxContent) }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>