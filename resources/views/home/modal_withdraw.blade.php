<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">Withdraw from Wallet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="withdraw_amount">Amount</label>
                    <input type="number" class="form-control" id="withdraw_amount" placeholder="Enter amount">
                </div>
                <div class="form-group">
                    <label for="withdraw_bank">Bank Name</label>
                    <input type="text" class="form-control" id="withdraw_bank" placeholder="Enter bank name">
                </div>
                <div class="form-group">
                    <label for="withdraw_account">Account Number</label>
                    <input type="text" class="form-control" id="withdraw_account" placeholder="Enter account number">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btn_withdraw">Withdraw</button>
            </div>
        </div>
    </div>
</div>
