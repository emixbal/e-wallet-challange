<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Login</h2>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter password">
            </div>
            <button class="btn btn-primary btn-block" id="btn_login">Login</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        var base_app_url = "{{ url('') }}" + "/api";
    </script>
    <script src="{{ asset('assets/js/login.js') }}"></script>
    <script>
        $(document).ready(function() {
            var accessToken = localStorage.getItem('access_token');
            if (accessToken) {
                window.location.href = "{{ route('home') }}";
                return;
            }

            $("#btn_login").on("click", function() {
                var email = $("#email").val()
                var password = $("#password").val()

                if (email == "") {
                    alert("email required !")
                    return
                }

                if (password == "") {
                    alert("password required !")
                    return
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: `${base_app_url}/login`,
                    type: "POST",
                    data: {
                        email,
                        password
                    },
                    success: async function(response, textStatus, xhr) {
                        var access_token = response?.data?.access_token;
                        if (access_token) {
                            localStorage.setItem('access_token', access_token);
                            // Redirect to home page
                            window.location.href = "{{ route('home') }}";
                        } else {
                            alert("Failed to retrieve access token.");
                            return;
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (jqXHR.status === 422) {
                            var errorMessage = jqXHR.responseJSON?.message ||
                                "Validation error occurred.";
                            alert(errorMessage);
                            return
                        } else {
                            console.log("An error occurred");
                            console.log(jqXHR, textStatus, errorThrown);
                            return;
                        }
                    }
                });
            })
        })
    </script>
</body>

</html>
