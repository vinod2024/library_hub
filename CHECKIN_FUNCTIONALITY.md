# Library Management System - Check-in Functionality

## Overview
This document describes the check-in and check-out functionality implemented for students in the library management system.

## Features Implemented

### 1. Timeslot Validation
- Students can only check-in/check-out during their assigned timeslot
- If a student tries to check-in outside their timeslot, an error message is displayed
- Timeslot format: HH:MM (e.g., "09:00" - "17:00")

### 2. Seat Selection
- When a student clicks "Check In", the system validates their timeslot
- If within timeslot, available seats are displayed for selection
- Students can click on any available seat to select it
- Selected seat is highlighted with green border
- Confirm button is enabled only after seat selection

### 3. Seat Assignment
- When a seat is selected and confirmed:
  - The seat's `assigned_to` field is updated with the student's profile ID
  - The seat's `status` is changed from 'vacant' to 'occupied'
  - The student's profile is updated with the seat ID
  - A timesheet record is created with check-in time

### 4. Check-out Process
- Students can only check-out if they have checked in
- Check-out frees up the assigned seat
- Seat status is reset to 'vacant' and `assigned_to` is set to null
- Timesheet is updated with check-out time and status 'completed'

### 5. Dashboard Status
- Real-time display of check-in/check-out status
- Buttons are disabled appropriately based on current state
- Success/error messages are displayed with auto-hide after 5 seconds

## Database Structure

### Seats Table
```sql
- id (primary key)
- number (unique seat number)
- status (enum: 'vacant', 'occupied')
- assigned_to (foreign key to student_profiles.id, nullable)
```

### Timesheets Table
```sql
- id (primary key)
- student_profile_id (foreign key)
- date (date)
- check_in (time, nullable)
- check_out (time, nullable)
- status (enum: 'pending', 'completed', 'overtime')
```

### Student Profiles Table
```sql
- id (primary key)
- user_id (foreign key to users)
- timeslot_start (time)
- timeslot_end (time)
- seat_id (foreign key to seats, nullable)
```

## Routes

- `POST /student/check-in` - Handle check-in process
- `POST /student/check-out` - Handle check-out process

## Views

- `student/dashboard.blade.php` - Main dashboard with check-in/out buttons
- `student/select-seat.blade.php` - Seat selection interface

## Usage Flow

1. **Student clicks "Check In"**
   - System validates timeslot
   - If outside timeslot: Show error message
   - If within timeslot: Show seat selection page

2. **Student selects seat**
   - Available seats are displayed in a grid
   - Student clicks on desired seat
   - Seat is highlighted and confirm button enabled

3. **Student confirms selection**
   - Seat is assigned to student
   - Check-in time is recorded
   - Success message displayed

4. **Student clicks "Check Out"**
   - System validates timeslot and check-in status
   - If valid: Seat is freed, check-out time recorded
   - Success message displayed

## Error Handling

- Timeslot validation with clear error messages
- Duplicate check-in prevention
- Seat availability validation
- Proper error messages for all scenarios

## Sample Data

The system includes 40 sample seats (A01-A20, B01-B20) created via database seeder.

## Testing

To test the functionality:
1. Create a student account
2. Join the library with a timeslot
3. Try checking in during and outside the timeslot
4. Select a seat and confirm
5. Try checking out
6. Verify seat assignment and timesheet records 