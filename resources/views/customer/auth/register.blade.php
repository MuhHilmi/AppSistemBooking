<form method="POST"
      action="{{ route('customer.register') }}">

    @csrf

    <input
        type="text"
        name="name"
        placeholder="Nama">

    <input
        type="text"
        name="phone"
        placeholder="Nomor HP">

    <input
        type="password"
        name="password"
        placeholder="Password">

    <button type="submit">
        Register
    </button>

</form>