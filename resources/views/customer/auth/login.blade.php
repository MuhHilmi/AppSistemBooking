<form method="POST"
      action="{{ route('customer.login') }}">

    @csrf

    <input
        type="text"
        name="phone"
        placeholder="Nomor HP">

    <input
        type="password"
        name="password"
        placeholder="Password">

    <button>
        Login
    </button>

</form>