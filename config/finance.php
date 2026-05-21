<?php

return [
    /*
     * Comma-separated emails that may mark seller invoices as paid (admin / finance).
     * Example: FINANCE_ADMIN_EMAILS=admin@example.com
     */
    'admin_emails' => array_filter(array_map('trim', explode(',', (string) env('FINANCE_ADMIN_EMAILS', '')))),
];
