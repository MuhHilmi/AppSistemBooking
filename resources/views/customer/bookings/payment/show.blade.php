@extends('layouts.customer')

@section('title', 'Booking | Payment')

@section('content')
    <div>
        <h1>Halaman Payment</h1>
    </div>
    <div>
        <a href="{{ route('customer.bookings.show') }}"><- Back</a>
    </div>
@endsection