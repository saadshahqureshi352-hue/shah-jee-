# TODO - Admin Panel ↔ Client Portal Connection

## Step 1
Locate Filament Booking resource/table queries and current actions.
- Done: Reviewed `app/Filament/Resources/BookingResource.php` and Filament booking pages.
- Found: No scoping/auth restriction in `BookingResource::table()`; actions can edit/update any Booking record.


## Step 2
Enforce admin-only access or restrict bookings query for non-admin roles.
- Done: `BookingResource::getEloquentQuery()` admin-only scoping added.
- Done: `Edit` + `Update Status` actions visible only for admin.


## Step 3
Make sure Filament booking status updates only change server DB in the same way as client portal.
- Validate status values mapping differences (pending/dispatched/delivered/returned vs picked_up etc.).

## Step 4
Add/adjust authorization policy (Filament) so non-admin users cannot edit bookings.

## Step 5
Testing
- Test client portal booking creation appears in admin panel.
- Test admin status update reflected in client portal.

