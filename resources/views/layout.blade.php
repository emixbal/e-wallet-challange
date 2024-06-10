<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page['parent_title'] }} - {{ $page['title'] }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    @yield('css')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">{{ $page['parent_title'] }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#payModal">Pay</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#topUpModal">Top Up</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>


    <script>
        var base_app_url = "{{ url('') }}";
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @yield('js')
</body>

</html>
