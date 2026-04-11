<div class="order-card" order-id="{{$id}}">
    <h3>Order #{{$id}}</h3>
    <p>Customer: {{$name}}</p>
    <p>Location: {{$city}}</p>
    <p>Price: ${{$price}}</p>
   <a href="/order/{{$id}}"> <button class="show-order-btn">Show Order</button></a>
    <a href="/order/edit/{{$id}}"><button class="edit-btn">Edit</button></a>
    <button class="delete-btn" onclick="openDialog({{$id}},'{{$name}}')">Delete</button>
</div>