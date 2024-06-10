$(document).ready(function () {
    // add loading
    setTimeout(function () {
        $('#transactions').html('<div class="text-center mt-5">Loading...</div>');
    }, 500);

    // delay dulu
    setTimeout(function () {
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
                            Belum ada transaksi.
                        </div>
                    `;
                    $('#transactions').append(noTransactionHTML);
                } else {
                    // iterate data
                    response.data.forEach(function (transaction) {
                        var transactionHTML = `
                            <div class="list-group-item transaction">
                                <div class="d-flex justify-content-between m-1">
                                    <div>
                                        <strong>${transaction.title}</strong>
                                        <div>${transaction.date}</div>
                                    </div>
                                    <div class="${transaction.amount >= 0 ? 'text-success' : 'text-danger'}">${transaction.amount}</div>
                                </div>
                            </div>
                        `;

                        // append to component
                        $('#transactions').append(transactionHTML);
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("terjadi kesalahan");
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }, 1000); // Delay 1000ms untuk permintaan Ajax
});
