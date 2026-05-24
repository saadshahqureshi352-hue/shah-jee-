<?php

return [
    // When true, Filament UI will render layout placeholders without touching the database.
    // This is useful when MySQL is offline.
    'enabled' => (bool) env('FILAMENT_LAYOUT_ONLY', false),
];

