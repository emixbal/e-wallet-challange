<!-- Top Up Modal -->
<div class="modal fade" id="topUpModal" tabindex="-1" aria-labelledby="topUpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="topUpModalLabel">Top Up Wallet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="topup-amount">Amount</label>
                        <input type="number" class="form-control" id="topup-amount" placeholder="Enter amount">
                    </div>
                    <div class="form-group">
                        <label for="payment-method">Payment Method</label>
                        <select class="form-control" id="payment-method">
                            <option>Bank Transfer</option>
                            <option>Credit Card</option>
                            <option>PayPal</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Top Up</button>
            </div>
        </div>
    </div>
</div>

