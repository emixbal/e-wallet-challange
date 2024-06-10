<!-- Pay Modal -->
<div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payModalLabel">Make a Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="payment_amount">Amount</label>
                        <input type="number" class="form-control" id="payment_amount" placeholder="Enter amount">
                    </div>
                    <div class="form-group">
                        <label for="payment_description">Description</label>
                        <input type="text" class="form-control" id="payment_description" placeholder="Enter description">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_payment">Pay</button>
            </div>
        </div>
    </div>
</div>
