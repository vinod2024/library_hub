@extends('layouts.admin')
@section('content')
<h1 class="mb-4">Students</h1>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered align-middle bg-white">
    <thead class="table-primary">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Student Details</th>
            <th scope="col">Seat / Payment Date</th>
            <th scope="col">Timeslot</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $key => $student)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Profile Photo" class="border" style="width: 100px; height: 120px; object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:70px;height:70px;font-size:2rem;">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h5 class="mb-1">{{ ucwords($student->user->name ?? '-') }}</h5>
                        <ul class="list-unstyled mb-0 text-secondary">
                            <li><i class="bi bi-person-circle me-2"></i> {{ $student->register_no ?? '-' }}</li>
                            <li><i class="bi bi-telephone me-2"></i> {{ $student->mobile ?? '-' }}</li>
                            <li><i class="bi bi-envelope me-2"></i> {{ $student->user->email ?? '-' }}</li>
                            <li><i class="bi bi-person-vcard me-2"></i>
                                <a href="{{ asset('storage/' . $student->id_proof) }}" target="_blank">
                                    ID Proof
                                </a> 
                            </li>
                           

                        </ul>
                    </div>
                </div>
            </td>
            <td>
                @if($student->seat)
                    Seat: {{ $student->seat->number }}
                    @if($student->seat->is_reserved)
                        <i class="bi bi-lock-fill text-secondary ms-1" title="Reserved Seat"></i>
                    @endif
                @else
                    --
                @endif
                @if($student->joining_date)
                    <div class="mt-1 p-1 rounded text-success">
                        <i class="bi bi-calendar-plus me-1" title="Joining Date"></i>
                        Joining:
                        {{ Carbon\Carbon::parse($student->joining_date)->format('d M Y') }}
                    </div>
                @endif
                @if($student->payment_due_date)
                    @php
                        $dueDate = \Carbon\Carbon::parse($student->payment_due_date);
                        $today = \Carbon\Carbon::today();
                        $diff = $today->diffInDays($dueDate, false);
                        $bgClass = '';
                        if ($dueDate->lt($today) || $dueDate->isSameDay($today)) {
                            $bgClass = 'bg-danger text-white';
                        } elseif ($diff > 0 && $diff <= 2) {
                            $bgClass = 'bg-warning text-dark';
                        }
                    @endphp
                    <div class="mt-1 p-1 rounded {{ $bgClass }}">
                        <i class="bi bi-calendar-event me-1" title="Payment Due Date"></i>
                        {{ $dueDate->format('d M Y') }}
                    </div>
                @endif

                
            </td>
            <td>
                <span class="badge bg-secondary text-white mb-1">Slot 1: {{ Carbon\Carbon::parse($student->timeslot_1_start)->format('h:i A') }} - {{ Carbon\Carbon::parse($student->timeslot_1_end)->format('h:i A') }}</span><br>
                @if($student->timeslot_2_start)
                <span class="badge bg-success text-white mb-1">Slot 2: {{ Carbon\Carbon::parse($student->timeslot_2_start)->format('h:i A') }} - {{ Carbon\Carbon::parse($student->timeslot_2_end)->format('h:i A') }}</span><br>
                @endif
                @if($student->timeslot_3_start)
                <span class="badge bg-warning text-dark mb-1">Slot 3: {{ Carbon\Carbon::parse($student->timeslot_3_start)->format('h:i A') }} - {{ Carbon\Carbon::parse($student->timeslot_3_end)->format('h:i A') }}</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" >Delete</button>
                </form>
                <button type="button" class="btn btn-sm btn-success pay-now-btn" data-student-id="{{ $student->id }}">Pay Now</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endsection 

@push('modals')
<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Student Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="paymentModalBody">
        <!-- Payment form will be loaded here -->
        <div class="text-center py-5">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Success Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
  <div id="paymentSuccessToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="paymentSuccessToastBody">
        Payment record saved successfully.
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('.pay-now-btn').on('click', function() {
        var studentId = $(this).data('student-id');
        $('#paymentModalBody').html('<div class="text-center py-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#paymentModal').modal('show');
        $.get('/admin/students/' + studentId + '/payment-form', function(data) {
            $('#paymentModalBody').html(data);
        });
    });

    // Delegate submit for dynamically loaded form
    $(document).on('submit', '#studentPaymentForm', function(e) {
        e.preventDefault();
        var form = $(this);
        var studentId = form.data('student-id');
        var formData = form.serialize();
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();
        $.ajax({
            url: '/admin/students/' + studentId + '/payment',
            method: 'POST',
            data: formData,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                $('#paymentModal').modal('hide');
                // Show toast with success message
                $('#paymentSuccessToastBody').text(response.message || 'Payment record saved successfully.');
                var toast = new bootstrap.Toast(document.getElementById('paymentSuccessToast'));
                toast.show();
                setTimeout(function() { location.reload(); }, 1200);
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        var input = form.find('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                    });
                }
            }
        });
    });
});
</script>
@endpush 