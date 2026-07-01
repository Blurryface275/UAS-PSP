<form action="{{ route('orders.checkout') }}" method="POST">
    @csrf
    <input type="number" name="product_id" ... >
    <input type="number" name="qty" ... >
    <button type="submit">Bayar Sekarang</button>
</form>