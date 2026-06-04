<?php

return [
    'bank' => [
        'label' => 'Bank Transfer',
        'bank_name' => env('SJC_BANK_NAME', 'Faysal Bank'),
        'account_holder' => env('SJC_BANK_ACCOUNT_HOLDER', 'Shah Jee Courier'),
        'account_no' => env('SJC_BANK_ACCOUNT_NO', '0000000000000000'),
        'iban' => env('SJC_BANK_IBAN', 'PK00XXXX0000000000000000'),
    ],
    'wallets' => [
        'jazzcash' => [
            'label' => 'JazzCash',
            'account_holder' => env('SJC_JAZZCASH_HOLDER', 'Shah Jee Courier'),
            'account_no' => env('SJC_JAZZCASH_NO', '03000000000'),
        ],
        'easypaisa' => [
            'label' => 'Easypaisa',
            'account_holder' => env('SJC_EASYPAISA_HOLDER', 'Shah Jee Courier'),
            'account_no' => env('SJC_EASYPAISA_NO', '03000000000'),
        ],
        'nayapay' => [
            'label' => 'NayaPay',
            'account_holder' => env('SJC_NAYAPAY_HOLDER', 'Shah Jee Courier'),
            'account_no' => env('SJC_NAYAPAY_NO', '03000000000'),
        ],
        'upaisa' => [
            'label' => 'UPaisa',
            'account_holder' => env('SJC_UPAISA_HOLDER', 'Shah Jee Courier'),
            'account_no' => env('SJC_UPAISA_NO', '03000000000'),
        ],
    ],
];
