@extends('layouts.student')
@section('content')
<style>
    .join-form-bg {
        background: linear-gradient(135deg, #e3f0ff 0%, #b3d8fd 100%);
        border-radius: 1rem;
        box-shadow: 0 2px 16px rgba(0,0,0,0.08);
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
    }
    .join-form-header {
        background: #1976d2;
        color: #fff;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        padding: 1.5rem 1rem 1rem 1rem;
        margin: -2rem -1.5rem 2rem -1.5rem;
        box-shadow: 0 2px 8px rgba(25,118,210,0.08);
    }
    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1976d2;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    .form-section-title i {
        margin-right: 0.5rem;
    }
    .btn-join {
        background: #1976d2;
        color: #fff;
        font-weight: 600;
        border-radius: 0.5rem;
        padding: 0.75rem 2.5rem;
        font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(25,118,210,0.08);
        transition: background 0.2s;
    }
    .btn-join:hover {
        background: #125ea2;
        color: #fff;
    }
    .file-upload-container {
        position: relative;
        border: 2px dashed #dee2e6;
        border-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
    .file-upload-container:hover {
        border-color: #1976d2;
        background: #e3f0ff;
    }
    .file-upload-container.dragover {
        border-color: #1976d2;
        background: #e3f0ff;
    }
    .file-upload-container.has-file {
        border-color: #28a745;
        background: #d4edda;
    }
    .file-preview {
        margin-top: 1rem;
        max-width: 200px;
        max-height: 200px;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .file-info {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #6c757d;
    }
    .validation-error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .validation-success {
        color: #28a745;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .file-requirements {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    .border-danger {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 2px #dc354533;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<div class="join-form-bg">
    <div class="join-form-header">
        <h2 class="mb-0"><i class="bi bi-person-plus me-2"></i>Join Library</h2>
        <p class="mb-0">Fill out the form below to join the library and reserve your seat.</p>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('student.join.submit') }}" enctype="multipart/form-data" id="joinForm">
        @csrf
        <div class="form-section-title"><i class="bi bi-person-lines-fill"></i>Personal Information</div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile') }}" required>
                @error('mobile')
                    <div class="validation-error">{{ $message }}</div>
                @enderror
                <div class="file-requirements">Format: Numbers, spaces, hyphens, parentheses, and plus signs allowed</div>
            </div>
            <div class="col-md-6">
                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                @error('address')
                    <div class="validation-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="form-section-title"><i class="bi bi-camera"></i>Photo & ID Proof</div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="photo" class="form-label">Photo <span class="text-danger">*</span></label>
                <div class="file-upload-container" id="photoContainer">
                    <i class="bi bi-camera fs-1 text-muted mb-2"></i>
                    <p class="mb-2">Click to upload or drag and drop</p>
                    <input type="file" class="form-control d-none" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('photo').click()">
                        <i class="bi bi-upload me-1"></i>Choose Photo
                    </button>
                    <div class="file-requirements">
                        Accepted formats: JPEG, PNG, JPG<br>
                        Max size: 5MB<br>
                        Dimensions: max 2000x2000 pixels
                    </div>
                    <div id="photoPreview"></div>
                    <div id="photoValidation"></div>
                </div>
                @error('photo')
                    <div class="validation-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="id_proof" class="form-label">ID Proof <span class="text-danger">*</span></label>
                <div class="file-upload-container" id="idProofContainer">
                    <i class="bi bi-file-earmark-text fs-1 text-muted mb-2"></i>
                    <p class="mb-2">Click to upload or drag and drop</p>
                    <input type="file" class="form-control d-none" id="id_proof" name="id_proof" accept="image/jpeg,image/png,image/jpg,application/pdf">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('id_proof').click()">
                        <i class="bi bi-upload me-1"></i>Choose ID Proof
                    </button>
                    <div class="file-requirements">
                        Accepted ID Proof: Aadhar, PAN, Voter ID, Driving Licence.<br>
                        Accepted formats: JPEG, PNG, JPG, PDF<br>
                        Max size: 5MB
                    </div>
                    <div id="idProofPreview"></div>
                    <div id="idProofValidation"></div>
                </div>
                @error('id_proof')
                    <div class="validation-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="form-section-title"><i class="bi bi-clock-history"></i>Library Usage</div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="timeslot_1_start" class="form-label">Timeslot 1 Start <span class="text-danger">*</span></label>
                <input type="time" class="form-control @error('timeslot_1_start') is-invalid @enderror" id="timeslot_1_start" name="timeslot_1_start" value="{{ old('timeslot_1_start') }}" required>
                @error('timeslot_1_start')
                    <div class="validation-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="timeslot_1_end" class="form-label">Timeslot 1 End <span class="text-danger">*</span></label>
                <input type="time" class="form-control @error('timeslot_1_end') is-invalid @enderror" id="timeslot_1_end" name="timeslot_1_end" value="{{ old('timeslot_1_end') }}" required>
                @error('timeslot_1_end')
                    <div class="validation-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="timeslot_2_start" class="form-label">Timeslot 2 Start </label>
                <input type="time" class="form-control @error('timeslot_2_start') is-invalid @enderror" id="timeslot_2_start" name="timeslot_2_start" value="{{ old('timeslot_2_start') }}" >
                @error('timeslot_2_start')
                    <div class="validation-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="timeslot_2_end" class="form-label">Timeslot 2 End  </label>
                <input type="time" class="form-control" id="timeslot_2_end" name="timeslot_2_end" value="{{ old('timeslot_2_end') }}" >
                
            </div>
            <div class="col-md-6">
                <label for="timeslot_3_start" class="form-label">Timeslot 3 Start </label>
                <input type="time" class="form-control" id="timeslot_3_start" name="timeslot_3_start" value="{{ old('timeslot_3_start') }}" >
                
            </div>
            <div class="col-md-6">
                <label for="timeslot_3_end" class="form-label">Timeslot 3 End </label>
                <input type="time" class="form-control" id="timeslot_3_end" name="timeslot_3_end" value="{{ old('timeslot_3_end') }}" >
                
            </div>
            <div class="col-md-6">
                <label for="joining_date" class="form-label">Joining Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('joining_date') is-invalid @enderror" id="joining_date" name="joining_date" value="{{ old('joining_date') }}" required>
                @error('joining_date')
                    <div class="validation-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-join" id="submitBtn">
                <i class="bi bi-check2-circle me-2"></i>Join Library
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for joining_date to today
    const today = new Date().toISOString().split('T')[0];
    // document.getElementById('joining_date').min = today;
    
    let isPhotoValid = false;

    // --- Add image resize helper ---
    function resizeImage(file, maxWidth, maxHeight, callback) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                let width = img.width;
                let height = img.height;
                // Calculate new dimensions
                if (width > maxWidth || height > maxHeight) {
                    if (width / height > maxWidth / maxHeight) {
                        height = Math.round(height * (maxWidth / width));
                        width = maxWidth;
                    } else {
                        width = Math.round(width * (maxHeight / height));
                        height = maxHeight;
                    }
                }
                // Create canvas and draw image
                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                // Convert canvas to Blob
                canvas.toBlob(function(blob) {
                    // Create a new File object
                    const resizedFile = new File([blob], file.name, { type: file.type });
                    callback(resizedFile, canvas.toDataURL(file.type));
                }, file.type, 0.95); // 0.95 quality
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
    // --- End image resize helper ---

    // --- Update validatePhoto to use resizeImage ---
    function validatePhoto(file) {
        const container = document.getElementById('photoContainer');
        const validation = document.getElementById('photoValidation');
        const preview = document.getElementById('photoPreview');
        
        // Reset
        container.classList.remove('has-file');
        validation.innerHTML = '';
        preview.innerHTML = '';
        isPhotoValid = false;
        
        if (!file) return false;
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!allowedTypes.includes(file.type)) {
            validation.innerHTML = '<div class="validation-error">Please select a valid image file (JPEG, PNG, JPG)</div>';
            return false;
        }
        
        // Check file size (5MB = 5 * 1024 * 1024 bytes)
        if (file.size > 5 * 1024 * 1024) {
            validation.innerHTML = '<div class="validation-error">File size must not exceed 5MB</div>';
            return false;
        }

        // Resize image before preview and validation
        resizeImage(file, 200, 280, function(resizedFile, dataUrl) {
            // Check dimensions again (should be <= 2000x2000)
            const img = new Image();
            img.onload = function() {
                if (this.width > 2000 || this.height > 2000) {
                    validation.innerHTML = '<div class="validation-error">Image dimensions must be less than 2000x2000 pixels</div>';
                    container.classList.remove('has-file');
                    preview.innerHTML = '';
                    isPhotoValid = false;
                } else {
                    validation.innerHTML = '<div class="validation-success">✓ Photo validated and resized successfully</div>';
                    container.classList.add('has-file');
                    preview.innerHTML = `<img src="${dataUrl}" class="file-preview" alt="Photo preview">`;
                    isPhotoValid = true;
                    // Replace the file in the input with the resized file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(resizedFile);
                    document.getElementById('photo').files = dataTransfer.files;
                }
            };
            img.src = dataUrl;
        });
        return true;
    }
    // --- End update validatePhoto ---
    
    function validateIdProof(file) {
        const container = document.getElementById('idProofContainer');
        const validation = document.getElementById('idProofValidation');
        const preview = document.getElementById('idProofPreview');
        
        // Reset
        container.classList.remove('has-file');
        validation.innerHTML = '';
        preview.innerHTML = '';
        
        if (!file) return false;
        
        // Check file type or extension
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        const fileName = file.name || '';
        const isPdfByExt = fileName.toLowerCase().endsWith('.pdf');
        if (!allowedTypes.includes(file.type) && !isPdfByExt) {
            validation.innerHTML = '<div class="validation-error">Please select a valid file (JPEG, PNG, JPG, PDF)</div>';
            return false;
        }
        
        // Check file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 5 * 1024 * 1024) {
            validation.innerHTML = '<div class="validation-error">File size must not exceed 5MB</div>';
            return false;
        }
        
        validation.innerHTML = '<div class="validation-success">✓ ID Proof validated successfully</div>';
        container.classList.add('has-file');
        
        // Show preview for images
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="file-preview" alt="ID Proof preview">`;
            };
            reader.readAsDataURL(file);
        } else if (isPdfByExt || file.type === 'application/pdf') {
            preview.innerHTML = `<div class="file-info"><i class="bi bi-file-pdf text-danger"></i> PDF Document</div>`;
        }
        
        return true;
    }
    
    // File input event listeners
    document.getElementById('photo').addEventListener('change', function(e) {
        validatePhoto(e.target.files[0]);
    });
    
    document.getElementById('id_proof').addEventListener('change', function(e) {
        validateIdProof(e.target.files[0]);
    });
    
    // Drag and drop functionality
    function setupDragAndDrop(containerId, inputId, validator) {
        const container = document.getElementById(containerId);
        const input = document.getElementById(inputId);
        
        container.addEventListener('dragover', function(e) {
            e.preventDefault();
            container.classList.add('dragover');
        });
        
        container.addEventListener('dragleave', function(e) {
            e.preventDefault();
            container.classList.remove('dragover');
        });
        
        container.addEventListener('drop', function(e) {
            e.preventDefault();
            container.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                input.files = files;
                validator(files[0]);
            }
        });
    }
    
    setupDragAndDrop('photoContainer', 'photo', validatePhoto);
    setupDragAndDrop('idProofContainer', 'id_proof', validateIdProof);
    
    // Form submission validation
    document.getElementById('joinForm').addEventListener('submit', function(e) {
        const photo = document.getElementById('photo').files[0];
        const idProof = document.getElementById('id_proof').files[0];
        const submitBtn = document.getElementById('submitBtn');

        let firstErrorField = null;
        let hasError = false;

        if (!photo) {
            e.preventDefault();
            document.getElementById('photoValidation').innerHTML = '<div class="validation-error">Photo is required</div>';
            if (!firstErrorField) firstErrorField = document.getElementById('photo');
            hasError = true;
        }
        if (!idProof) {
            e.preventDefault();
            document.getElementById('idProofValidation').innerHTML = '<div class="validation-error">ID Proof is required</div>';
            if (!firstErrorField) firstErrorField = document.getElementById('id_proof');
            hasError = true;
        }
        if (!isPhotoValid || (idProof && !validateIdProof(idProof))) {
            e.preventDefault();
            if (!isPhotoValid) {
                document.getElementById('photoValidation').innerHTML = '<div class="validation-error">Photo is not valid. Please select a valid image.</div>';
                if (!firstErrorField) firstErrorField = document.getElementById('photo');
                hasError = true;
            }
        }
        if (hasError && firstErrorField) {
            // Find the visible container to focus/highlight
            let container = null;
            if (firstErrorField.id === 'photo') {
                container = document.getElementById('photoContainer');
            } else if (firstErrorField.id === 'id_proof') {
                container = document.getElementById('idProofContainer');
            }
            if (container) {
                container.scrollIntoView({ behavior: "smooth", block: "center" });
                // Add a highlight effect
                container.classList.add('border-danger');
                setTimeout(() => container.classList.remove('border-danger'), 1500);
                // Optionally, focus the first button inside the container
                const btn = container.querySelector('button');
                if (btn) btn.focus();
            }
            return false;
        }

        // Disable the button and show "Joining..." with spinner
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Joining...';
    });
    
    // Time validation
    document.getElementById('timeslot_end').addEventListener('change', function() {
        const startTime = document.getElementById('timeslot_start').value;
        const endTime = this.value;
        
        if (startTime && endTime && startTime >= endTime) {
            this.setCustomValidity('End time must be after start time');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endsection 