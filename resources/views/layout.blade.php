<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page['parent_title'] }} - {{ $page['title'] }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);" id="btn_logout">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>


    <script>
        var base_app_url = "{{ url('') }}/api";
        // Check if access token already exists
        var accessToken = localStorage.getItem('access_token');
        if (!accessToken) {
            window.location.href = "{{ route('login') }}";
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @yield('js')

    <script>
        $(document).ready(function() {
            // Handle logout button click
            $("#btn_logout").on("click", function() {
                // Remove the access token from local storage
                localStorage.removeItem('access_token');

                // Redirect to the login page
                window.location.href = "{{ route('login') }}";
                return
            });
        });
    </script>
</body>

</html>
