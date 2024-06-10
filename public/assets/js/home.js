$(document).ready(function () {
    // add loading
    setTimeout(function () {
        $('#transactions').html('<div class="text-center mt-5">Loading...</div>');
        $('#wallet_balance').html('<div class="text-center mt-5">Loading...</div>');
    }, 500);

    // delay first
    setTimeout(function () {
        // get wallet balance
        $.ajax({
            headers: {
                'Authorization': accessToken,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `${base_app_url}/wallet-detail`,
            type: "GET",
            success: async function (response, textStatus, xhr) {
                $('#wallet_balance').empty();
                var balance = response?.data?.wallet?.balance;

                // convert balance to Rupiah format
                var formattedBalance = formatRupiah(balance);

                $('#wallet_balance').html('<div class="text-center mt-5">' + formattedBalance + '</div>');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error occurred while fetching wallet balance");
                console.log(jqXHR, textStatus, errorThrown);
                $('#wallet_balance').html('<div class="text-center mt-5">Error occurred while fetching wallet balance</div>');
            }
        });

        // get transactions
        $.ajax({
            headers: {
                'Authorization': accessToken,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `${base_app_url}/transactions`,
            type: "GET",
            success: async function (response, textStatus, xhr) {
                $('#transactions').empty();

                if (response.data.length === 0) {
                    var noTransactionHTML = `
                        <div class="list-group-item">
                            No transactions yet.
                        </div>
                    `;
                    $('#transactions').append(noTransactionHTML);
                } else {
                    // Iterate through data
                    response.data.forEach(function (transaction) {
                        var transactionHTML = `
                            <div class="list-group-item transaction">
                                <div class="d-flex justify-content-between m-1">
                                    <div>
                                        <strong>${transaction.order_id}</strong>
                                        <div>${transaction.timestamp}</div>
                                    </div>
                                    <div class="${transaction.amount >= 0 ? 'text-success' : 'text-danger'}">${formatRupiah(transaction.amount)}</div>
                                </div>
                            </div>
                        `;

                        // Append to component
                        $('#transactions').append(transactionHTML);
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error occurred while fetching transactions");
                console.log(jqXHR, textStatus, errorThrown);
                $('#transactions').html('<div class="text-center mt-5">Error occurred while fetching transactions</div>');
            }
        });
    }, 1000); // Delay 1000ms for Ajax request


    $("#btn_top_up").on("click", function () {
        var topup_amount = $("#topup_amount").val()

        if (topup_amount=="") {
            alert("Amount required !")
            return
        }

        if (isNaN(topup_amount)) {
            alert("Amount top-up harus berupa angka!");
            return; // Keluar dari fungsi jika tidak valid
        }

        $.ajax({
            headers: {
                'Authorization': accessToken,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `${base_app_url}/deposit`,
            type: "POST",
            data: {
                amount: topup_amount,
            },
            success: async function (response, textStatus, xhr) {
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status === 422) {
                    var errorMessage = jqXHR.responseJSON?.message || "Validation error occurred.";
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
});

// Function to format number to Rupiah format
function formatRupiah(number) {
    var number_string = number.toString(),
        remainder = number_string.length % 3,
        rupiah = number_string.substr(0, remainder),
        thousands = number_string.substr(remainder).match(/\d{3}/g);

    if (thousands) {
        separator = remainder ? '.' : '';
        rupiah += separator + thousands.join('.');
    }
    return 'Rp ' + rupiah;
}


