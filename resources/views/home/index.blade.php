@extends('layout')

@section('content')
    <div class="text-center mt-5">
        <h1>{{ $page['parent_title'] }}</h1>
    </div>
    <div class="text-center wallet-balance">
        $1,250.00
    </div>
    <div class="list-group mt-4">
        <div class="list-group-item transaction">
            <div class="d-flex justify-content-between m-1">
                <div>
                    <strong>Amazon Purchase</strong>
                    <div>March 20, 2024</div>
                </div>
                <div class="text-danger">- $150.00</div>
            </div>
        </div>
        <div class="list-group-item transaction">
            <div class="d-flex justify-content-between m-1">
                <div>
                    <strong>Salary</strong>
                    <div>March 15, 2024</div>
                </div>
                <div class="text-success">+ $1,500.00</div>
            </div>
        </div>
        <div class="list-group-item transaction">
            <div class="d-flex justify-content-between m-1">
                <div>
                    <strong>Starbucks</strong>
                    <div>March 12, 2024</div>
                </div>
                <div class="text-danger">- $25.00</div>
            </div>
        </div>
        <div class="list-group-item transaction">
            <div class="d-flex justify-content-between m-1">
                <div>
                    <strong>Electric Bill</strong>
                    <div>March 10, 2024</div>
                </div>
                <div class="text-danger">- $100.00</div>
            </div>
        </div>
    </div>

    <!-- Pay Button -->
    <button type="button" class="btn btn-primary btn-float" data-toggle="modal" data-target="#payModal">
        Pay
    </button>

    @include('home.modal_topup')
    @include('home.modal_pay')

@endsection


@section('js')
    <script src="{{ asset('assets/js/home.js') }}"></script>
@stop
