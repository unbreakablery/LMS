@extends('layouts.app')

@section('content')
<div class="container no-max-width main-container position-relative">
    @include('left-menu')
    <div class="row">
        <main role="main" class="main-content">
            <div class="row ml-0 mr-0 justify-content-center">
                <div class="welcome-section col-lg-4 col-md-6 col-sm-12">
                    <h1 class="mt-4">Welcome to LMS!</h1>
                    <h2 class="mt-4 mb-4">What can you do here?</h2>
                    <ul class="to-do-list">
                        @if (Auth::user()->role == 1)
                        <li>Manage staffs</li>
                        <li>Manage students</li>
                        <li>Manage categories</li>
                        <li>Manage equipment</li>
                        <li>Manage bookings</li>
                        <li>Track equipment</li>
                        <li>See historical reports</li>
                        <li>Manage notifications</li>
                        <li>Manage your account information</li>
                        @endif
                        @if (Auth::user()->role == 2)
                        <li>Manage categories</li>
                        <li>Manage equipment</li>
                        <li>Manage bookings</li>
                        <li>Track equipment</li>
                        <li>See historical reports</li>
                        <li>Manage your account information</li>
                        @endif
                        @if (Auth::user()->role == 3)
                        <li>Search/find the equipment for booking</li>
                        <li>Booking equipment</li>
                        <li>Pickup equipment</li>
                        <li>Return equipment</li>
                        <li>View Your historical data</li>
                        <li>Manage your account information</li>
                        @endif
                    </ul>
                </div>    
            </div>
        </main>
    </div>
</div>
@endsection
