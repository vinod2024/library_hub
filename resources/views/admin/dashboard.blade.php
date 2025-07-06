@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    .overstay-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        animation: slideInRight 0.5s ease-out;
        box-shadow: 0 4px 20px rgba(220, 53, 69, 0.3);
        border: 2px solid #dc3545;
    }
    
    .overstay-alert {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10000;
        background: #dc3545;
        color: white;
        padding: 2rem;
        border-radius: 10px;
        text-align: center;
        animation: pulseAlert 2s infinite;
        box-shadow: 0 0 50px rgba(220, 53, 69, 0.8);
    }
    
    @keyframes pulseAlert {
        0% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.05); }
        100% { transform: translate(-50%, -50%) scale(1); }
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .overstay-badge {
        position: relative;
    }
    
    .overstay-count {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
    
    .overstay-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .overstay-item {
        border-left: 4px solid #dc3545;
        background: #fff3cd;
        margin-bottom: 8px;
        padding: 10px;
        border-radius: 4px;
    }
    
    .overstay-item .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .overstay-item .btn-group .btn:hover {
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }
</style>
<div class="mb-4">
    <h2 class="fw-bold mb-3"><i class="bi bi-speedometer2 me-2"></i>Admin Dashboard</h2>
    <p class="text-secondary">Welcome to the library management admin panel. Here are your key stats and quick links.</p>
</div>
<div class="row g-4 mb-4">
    <!-- Total Users Widget -->
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0 text-center bg-light">
            <div class="card-body">
                <div class="mb-2"><i class="bi bi-people-fill text-primary" style="font-size:2rem;"></i></div>
                <h5 class="card-title">Total Users</h5>
                <div class="display-6 fw-bold">{{ $totalUsers ?? '0' }}</div>
            </div>
        </div>
    </div>
    <!-- Total Students Widget -->
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0 text-center bg-info-subtle">
            <div class="card-body">
                <div class="mb-2"><i class="bi bi-person-badge text-info" style="font-size:2rem;"></i></div>
                <h5 class="card-title">Total Students</h5>
                <div class="display-6 fw-bold">{{ $totalStudents ?? '0' }}</div>
            </div>
        </div>
    </div>
    <!-- Total Seats Widget -->
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0 text-center bg-success-subtle">
            <div class="card-body">
                <div class="mb-2"><i class="bi bi-grid-3x3-gap-fill text-success" style="font-size:2rem;"></i></div>
                <h5 class="card-title">Total Seats</h5>
                <div class="display-6 fw-bold">{{ $totalSeats ?? '0' }}</div>
            </div>
        </div>
    </div>
    <!-- Vacant Seats Widget -->
    <div class="col-md-3 col-6">
        <div class="card shadow-sm border-0 text-center bg-warning-subtle">
            <div class="card-body">
                <div class="mb-2"><i class="bi bi-emoji-smile text-warning" style="font-size:2rem;"></i></div>
                <h5 class="card-title">Vacant Seats</h5>
                <div class="display-6 fw-bold">{{ $vacantSeats ?? '0' }}</div>
            </div>
        </div>
    </div>
</div>
<!-- Seating Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-grid-3x3-gap me-2"></i>Vacant Seats</h5>
            </div>
            <div class="card-body">
                <div class="row g-1">
                    @foreach($vacantSeatsList ?? [] as $seat)
                    <div class="col-md-1 col-sm-1 col-2 col-3">
                        <div class="card border-success text-center" style="min-height: 40px;">
                            <div class="card-body py-1 px-0">
                                <div class="fw-bold text-success" style="font-size: 0.9rem;">{{ $seat->number }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if(empty($vacantSeatsList))
                    <div class="col-12 text-center text-muted">
                        <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                        <p class="mt-1 mb-0">No vacant seats</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Overstay Monitoring Widget -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>Overstay Monitoring
                    <span class="overstay-badge">
                        <span class="overstay-count" id="overstay-count" style="display: none;">0</span>
                    </span>
                    <small class="ms-2" id="monitoring-status">
                        <i class="bi bi-circle-fill text-success" style="font-size: 0.6rem;"></i> <span>Auto-monitoring</span>
                        <span class="ms-2 text-light">| Last updated: <span id="last-updated-time">-</span></span>
                    </small>
                </h5>
                <button class="btn btn-sm btn-outline-light" onclick="refreshOverstays()">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </button>
            </div>
            <div class="card-body">
                <div id="overstay-content">
                    <div class="text-center text-muted">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No students are currently overstaying</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overstay Notification Container -->
<div id="overstay-notifications"></div>

<!-- Check-out Confirmation Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="checkoutModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirm Force Check-out
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to force check-out <strong id="checkoutStudentName"></strong>?</p>
                <p class="text-muted small">This action will:</p>
                <ul class="text-muted small">
                    <li>Mark the student as checked out</li>
                    <li>Free up their assigned seat</li>
                    <li>Update their timesheet record</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmCheckoutBtn" onclick="executeCheckOut()">
                    <i class="bi bi-box-arrow-left me-2"></i>Force Check-out
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let overstayCheckInterval;
let lastOverstayCount = 0;
let alertSound;
let hasShownAlert = false;

// Function to fetch overstay data
async function fetchOverstays() {
    try {
        const response = await fetch('{{ route("admin.overstays.api") }}');
        const data = await response.json();
        
        if (data.success) {
            updateOverstayDisplay(data.data, data.count);
            checkForNewOverstays(data.count);
            updateMonitoringStatus(true);
            
            // Update last updated time
            const now = new Date();
            document.getElementById('last-updated-time').textContent = now.toLocaleTimeString();
        }
    } catch (error) {
        console.error('Error fetching overstay data:', error);
        updateMonitoringStatus(false);
    }
}

// Function to update monitoring status
function updateMonitoringStatus(isActive) {
    const statusElement = document.getElementById('monitoring-status');
    if (statusElement) {
        const icon = statusElement.querySelector('i');
        const text = statusElement.querySelector('span');
        
        if (isActive) {
            icon.className = 'bi bi-circle-fill text-success';
            icon.style.fontSize = '0.6rem';
            if (text) text.textContent = ' Auto-monitoring';
        } else {
            icon.className = 'bi bi-circle-fill text-warning';
            icon.style.fontSize = '0.6rem';
            if (text) text.textContent = ' Connection issue';
        }
    }
}

// Function to update the overstay display
function updateOverstayDisplay(overstays, count) {
    const contentDiv = document.getElementById('overstay-content');
    const countSpan = document.getElementById('overstay-count');
    
    if (count === 0) {
        contentDiv.innerHTML = `
            <div class="text-center text-muted">
                <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                <p class="mt-2 mb-0">No students are currently overstaying</p>
            </div>
        `;
        countSpan.style.display = 'none';
    } else {
        countSpan.textContent = count;
        countSpan.style.display = 'flex';
        
        let overstayList = '<div class="overstay-list">';
        overstays.forEach(student => {
            overstayList += `
                <div class="overstay-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${student.user_name}</strong>
                            <br>
                            <small class="text-muted">
                                Seat: ${student.seat_number} | 
                                Timeslot: ${student.timeslot_start} - ${student.timeslot_end}
                            </small>
                            <br>
                            <small class="text-danger">
                                <i class="bi bi-clock"></i> Overstaying by ${student.overstay_duration}
                            </small>
                        </div>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-danger" onclick="sendReminder(${student.student_id})" title="Send Reminder">
                                <i class="bi bi-bell"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="forceCheckOut(${student.student_id}, '${student.user_name}')" title="Force Check-out">
                                <i class="bi bi-box-arrow-left"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        overstayList += '</div>';
        contentDiv.innerHTML = overstayList;
    }
}

// Function to check for new overstays and show notifications
function checkForNewOverstays(currentCount) {
    if (currentCount > lastOverstayCount && lastOverstayCount > 0) {
        // New overstays detected
        showOverstayNotification(currentCount - lastOverstayCount);
        showOverstayAlert(currentCount - lastOverstayCount);
        playAlertSound();
        showBrowserNotification(currentCount - lastOverstayCount);
    }
    
    // Show alert if this is the first time detecting overstays
    if (currentCount > 0 && lastOverstayCount === 0) {
        showOverstayAlert(currentCount);
        playAlertSound();
        showBrowserNotification(currentCount);
    }
    
    lastOverstayCount = currentCount;
}

// Function to show overstay notification
function showOverstayNotification(newCount) {
    const notificationContainer = document.getElementById('overstay-notifications');
    const notificationId = 'overstay-' + Date.now();
    
    const notification = document.createElement('div');
    notification.className = 'alert alert-danger overstay-notification';
    notification.id = notificationId;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.2rem;"></i>
            <div>
                <strong>Overstay Alert!</strong>
                <br>
                <small>${newCount} new student${newCount > 1 ? 's' : ''} ${newCount > 1 ? 'are' : 'is'} overstaying their timeslot.</small>
            </div>
            <button type="button" class="btn-close ms-auto" onclick="removeNotification('${notificationId}')"></button>
        </div>
    `;
    
    notificationContainer.appendChild(notification);
    
    // Auto-remove notification after 10 seconds
    setTimeout(() => {
        removeNotification(notificationId);
    }, 10000);
}

// Function to remove notification
function removeNotification(notificationId) {
    const notification = document.getElementById(notificationId);
    if (notification) {
        notification.remove();
    }
}

// Function to show prominent overstay alert
function showOverstayAlert(count) {
    const alertContainer = document.getElementById('overstay-notifications');
    const alertId = 'overstay-alert-' + Date.now();
    
    const alert = document.createElement('div');
    alert.className = 'overstay-alert';
    alert.id = alertId;
    alert.innerHTML = `
        <div>
            <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <h4>OVERSTAY ALERT!</h4>
            <p style="font-size: 1.2rem; margin-bottom: 1rem;">
                ${count} student${count > 1 ? 's' : ''} ${count > 1 ? 'are' : 'is'} overstaying their timeslot!
            </p>
            <button class="btn btn-light" onclick="removeAlert('${alertId}')">
                Acknowledge
            </button>
        </div>
    `;
    
    alertContainer.appendChild(alert);
    
    // Auto-remove alert after 15 seconds
    setTimeout(() => {
        removeAlert(alertId);
    }, 15000);
}

// Function to remove alert
function removeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.remove();
    }
}

// Function to play alert sound
function playAlertSound() {
    try {
        // Create a simple beep sound using Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime + 0.2);
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.3);
    } catch (error) {
        console.log('Could not play alert sound:', error);
    }
}

// Function to show browser notification
function showBrowserNotification(count) {
    if ('Notification' in window && Notification.permission === 'granted' && document.hidden) {
        const notification = new Notification('Overstay Alert!', {
            body: `${count} student${count > 1 ? 's' : ''} ${count > 1 ? 'are' : 'is'} overstaying their timeslot!`,
            icon: '/favicon.ico',
            tag: 'overstay-alert',
            requireInteraction: true
        });
        
        notification.onclick = function() {
            window.focus();
            this.close();
        };
        
        // Auto-close after 10 seconds
        setTimeout(() => {
            notification.close();
        }, 10000);
    }
}

// Function to refresh overstays manually
function refreshOverstays() {
    fetchOverstays();
}

// Function to send reminder (placeholder for future implementation)
function sendReminder(studentId) {
    // This could be implemented to send an email or SMS reminder
    alert('Reminder functionality will be implemented in the next phase.');
}

// Function to force check-out a student
function forceCheckOut(studentId, studentName) {
    // Show confirmation modal
    document.getElementById('checkoutStudentName').textContent = studentName;
    
    // Store the student data for the confirmation
    const modal = document.getElementById('checkoutModal');
    modal.dataset.studentId = studentId;
    modal.dataset.studentName = studentName;
    
    // Show the modal
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

// Function to execute the actual check-out after confirmation
async function executeCheckOut() {
    const modal = document.getElementById('checkoutModal');
    const studentId = modal.dataset.studentId;
    const studentName = modal.dataset.studentName;
    
    // Hide the modal
    const bootstrapModal = bootstrap.Modal.getInstance(modal);
    bootstrapModal.hide();
    
    // Find the button and show loading state
    const button = document.getElementById('confirmCheckoutBtn');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';
    button.disabled = true;
    
    try {
        const response = await fetch('{{ route("admin.overstays.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                student_id: studentId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show success notification
            showSuccessNotification(`${studentName} has been successfully checked out at ${data.check_out_time}`);
            
            // Refresh the overstay list immediately
            fetchOverstays();
        } else {
            showErrorNotification(data.message || 'Error processing check-out');
        }
    } catch (error) {
        console.error('Error during force check-out:', error);
        showErrorNotification('Network error occurred while processing check-out');
    } finally {
        // Restore button state
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// Function to show success notification
function showSuccessNotification(message) {
    const notificationContainer = document.getElementById('overstay-notifications');
    const notificationId = 'success-' + Date.now();
    
    const notification = document.createElement('div');
    notification.className = 'alert alert-success overstay-notification';
    notification.id = notificationId;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2" style="font-size: 1.2rem;"></i>
            <div>
                <strong>Success!</strong>
                <br>
                <small>${message}</small>
            </div>
            <button type="button" class="btn-close ms-auto" onclick="removeNotification('${notificationId}')"></button>
        </div>
    `;
    
    notificationContainer.appendChild(notification);
    
    // Auto-remove notification after 5 seconds
    setTimeout(() => {
        removeNotification(notificationId);
    }, 5000);
}

// Function to show error notification
function showErrorNotification(message) {
    const notificationContainer = document.getElementById('overstay-notifications');
    const notificationId = 'error-' + Date.now();
    
    const notification = document.createElement('div');
    notification.className = 'alert alert-danger overstay-notification';
    notification.id = notificationId;
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.2rem;"></i>
            <div>
                <strong>Error!</strong>
                <br>
                <small>${message}</small>
            </div>
            <button type="button" class="btn-close ms-auto" onclick="removeNotification('${notificationId}')"></button>
        </div>
    `;
    
    notificationContainer.appendChild(notification);
    
    // Auto-remove notification after 8 seconds
    setTimeout(() => {
        removeNotification(notificationId);
    }, 8000);
}

// Initialize overstay monitoring
document.addEventListener('DOMContentLoaded', function() {
    // Request notification permission
    if ('Notification' in window) {
        Notification.requestPermission();
    }
    
    // Initial fetch
    fetchOverstays();
    
    // Set up periodic checking (every 10 seconds for more responsive alerts)
    overstayCheckInterval = setInterval(fetchOverstays, 10000);
    
    // Also check immediately when the page becomes visible (when user switches tabs back)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            fetchOverstays();
        }
    });
});

// Clean up interval when page is unloaded
window.addEventListener('beforeunload', function() {
    if (overstayCheckInterval) {
        clearInterval(overstayCheckInterval);
    }
});
</script>

@endsection 