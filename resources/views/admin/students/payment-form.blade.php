<form id="studentPaymentForm" data-student-id="{{ $student->id }}">
    @csrf
    <div class="mb-3">
    <h5>{{ ucwords($student->user->name) . ' - ' . $student->register_no }}</h5>
    </div>
    <div class="mb-3">
        <label for="payment_method" class="form-label">Payment Method</label>
        <select class="form-select" id="payment_method" name="payment_method" required>
            <option value="">Select Method</option>
            <option value="Cash" {{ (old('payment_method', $payment->payment_method ?? '') == 'Cash') ? 'selected' : '' }}>Cash</option>
            <option value="Online" {{ (old('payment_method', $payment->payment_method ?? '') == 'Online') ? 'selected' : '' }}>Online</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="amount" class="form-label">Amount</label>
        <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount', $payment->amount ?? '') }}" required>
    </div>
    <div class="mb-3">
        <label for="payment_due_date" class="form-label">Payment Due Date</label>
        <input type="date" class="form-control" id="payment_due_date" name="payment_due_date" value="{{ old('payment_due_date', isset($payment->payment_due_date) ? $payment->payment_due_date : '') }}" required>
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success">Save Payment</button>
    </div>
</form> 