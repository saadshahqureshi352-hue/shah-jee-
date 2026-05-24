# TODO (Bookins table / SQLite migrations)

- [x] Identify crash: dashboard counts `bookings` table (SQLite says no such table).
- [x] Read booking controller and bookings migrations.
- [x] Disable SQLite-incompatible migration SQL: `ALTER TABLE ... MODIFY COLUMN status` in `2026_05_20_120000_add_order_fields_to_bookings_table.php`.
- [ ] Continue migration roll-forward on SQLite (remaining failures: duplicate columns due to partial/inconsistent sqlite schema).
- [ ] Recommended: delete `database/database.sqlite` (local) then rerun `php artisan migrate --force`.
- [ ] After schema is created, ensure `bookings` table includes `user_id` column (controller uses it).

