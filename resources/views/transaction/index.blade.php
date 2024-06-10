<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Transactions</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
            padding-top: 60px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Transactions</a>
                </li>
                <!-- Add other nav items here -->
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1 class="my-4">All Transactions</h1>
        <table class="table table-striped" id="transactions-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User Name</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <!-- Transaction rows will be appended here by jQuery -->
            </tbody>
        </table>
    </div>
    <script>
        var base_app_url = "{{ url('') }}/api";
        // Check if access token already exists
        var accessToken = localStorage.getItem('access_token');
        if (!accessToken) {
            window.location.href = "{{ route('login') }}";
        }

        $(document).ready(function() {
            function loadTransactions() {
                $.ajax({
                    url: base_app_url + '/admin-transactions', // Replace with your actual API endpoint
                    method: 'GET',
                    headers: {
                        'Authorization': accessToken,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        const transactionsTableBody = $('#transactions-table tbody');
                        transactionsTableBody.empty(); // Clear existing rows
                        data.data.forEach(function(transaction) {
                            const status = transaction.status === 1 ? 'Success' : 'Failed';
                            const row = `
                                <tr>
                                    <td>${transaction.order_id}</td>
                                    <td>${transaction.user_name}</td>
                                    <td>${transaction.amount.toFixed(2)}</td>
                                    <td>${status}</td>
                                    <td>${transaction.timestamp}</td>
                                </tr>
                            `;
                            transactionsTableBody.append(row);
                        });
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 403) {
                            // Clear access token from local storage
                            localStorage.removeItem('access_token');
                            // Redirect to home page or login page
                            window.location.href =
                            "{{ route('login') }}"; // Ganti dengan rute yang benar
                        } else {
                            console.error('Error fetching transactions:', error);
                        }
                    }
                });
            }

            // Initial load
            loadTransactions();
        });
    </script>
</body>

</html>
