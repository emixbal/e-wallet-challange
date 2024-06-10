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
                        var formattedDate = moment(transaction.timestamp).locale('id').format('DD MMMM YYYY');
                        var transactionClass = transaction.status === 1 ? 'text-success' : 'text-danger';
                        var statusDescription = '';

                        // Add description based on status
                        switch (transaction.status) {
                            case 1:
                                statusDescription = 'Deposit';
                                break;
                            case 2:
                                statusDescription = 'Withdraw';
                                break;
                            case 3:
                                statusDescription = 'Pembayaran';
                                break;
                            default:
                                statusDescription = 'Unknown';
                        }

                        var transactionHTML = `
                            <div class="list-group-item transaction">
                                <div class="d-flex justify-content-between m-1">
                                    <div>
                                        <strong>${transaction.order_id}</strong>
                                        <div>${formattedDate}</div>
                                        <small>${statusDescription}</small>
                                    </div>
                                    <div class="${transactionClass}">${formatRupiah(transaction.amount)}</div>
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

        if (topup_amount == "") {
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
                if (response.status == "nok") {
                    alert("Ulangi beberapa saat lagi")
                    $('#topUpModal').modal('hide');
                }
                location.reload();
                return;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status === 422) {
                    var errorMessage = jqXHR.responseJSON?.message || "Validation error occurred.";
                    alert(errorMessage);
                    return
                }

                console.log("An error occurred");
                console.log(jqXHR, textStatus, errorThrown);
                return;
            },
            finally: function () {
                alert("Ulangi beberapa saat lagi");
                return;
            }
        });
    })

    $("#btn_withdraw").on("click", function () {
        var withdraw_amount = $("#withdraw_amount").val()
        var withdraw_bank = $("#withdraw_bank").val()
        var withdraw_account = $("#withdraw_account").val()

        if (withdraw_amount == "") {
            alert("Amount required !")
            return
        }
        if (withdraw_bank == "") {
            alert("Bank destination required !")
            return
        }
        if (withdraw_account == "") {
            alert("Account destination required !")
            return
        }

        if (isNaN(withdraw_amount)) {
            alert("Amount withdraw harus berupa angka!");
            return; // Keluar dari fungsi jika tidak valid
        }

        $.ajax({
            headers: {
                'Authorization': accessToken,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `${base_app_url}/withdraw`,
            type: "POST",
            data: {
                amount: withdraw_amount,
                bank: withdraw_bank,
                account: withdraw_amount,
            },
            success: async function (response, textStatus, xhr) {
                if (response.status == "nok") {
                    alert("Ulangi beberapa saat lagi")
                    $('#topUpModal').modal('hide');
                }
                location.reload();
                return;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status === 422) {
                    var errorMessage = jqXHR.responseJSON?.message || "Validation error occurred.";
                    alert(errorMessage);
                    return
                }

                console.log("An error occurred");
                console.log(jqXHR, textStatus, errorThrown);
                return;
            },
            finally: function () {
                alert("Ulangi beberapa saat lagi");
                return;
            }
        });
    })

    $("#btn_payment").on("click", function () {
        alert()
        var payment_amount = $("#payment_amount").val()

        if (payment_amount == "") {
            alert("Amount required !")
            return
        }

        if (isNaN(payment_amount)) {
            alert("Amount withdraw harus berupa angka!");
            return;
        }

        $.ajax({
            headers: {
                'Authorization': accessToken,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `${base_app_url}/payment`,
            type: "POST",
            data: {
                amount: payment_amount,
            },
            success: async function (response, textStatus, xhr) {
                if (response.status == "nok") {
                    alert("Ulangi beberapa saat lagi")
                    $('#payModal').modal('hide');
                }
                location.reload();
                return;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status === 422) {
                    var errorMessage = jqXHR.responseJSON?.message || "Validation error occurred.";
                    alert(errorMessage);
                    return
                }

                console.log("An error occurred");
                console.log(jqXHR, textStatus, errorThrown);
                return;
            },
            finally: function () {
                alert("Ulangi beberapa saat lagi");
                return;
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


