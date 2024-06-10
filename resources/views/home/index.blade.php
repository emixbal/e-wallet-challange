@extends('layout')
@section('css')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .wallet-balance {
            font-size: 2rem;
            font-weight: bold;
            margin: 20px 0;
        }

        .transaction {
            border-bottom: 1px solid #dee2e6;
            padding: 15px 0;
            margin: 0 10px;
        }

        .transaction:last-child {
            border-bottom: none;
        }

        .btn-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            border-radius: 50%;
            padding: 15px 20px;
            font-size: 1.5rem;
            margin-left: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="text-center mt-5">
        <h1>{{ $page['parent_title'] }}</h1>
    </div>
    <div class="text-center wallet-balance">
        $1,250.00
    </div>
    <div class="list-group mt-4" id="transactions"></div>

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
