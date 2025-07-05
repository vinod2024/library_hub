@extends('layouts.student')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Test Form Submission</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('student.checkin') }}" id="test-form">
                        @csrf
                        <input type="hidden" name="seat_id" value="1">
                        <button type="submit" class="btn btn-primary">Test Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('test-form').addEventListener('submit', function(e) {
    console.log('Test form submitted');
    console.log('Method:', this.method);
    console.log('Action:', this.action);
    console.log('Seat ID:', this.querySelector('input[name="seat_id"]').value);
});
</script>
@endsection 