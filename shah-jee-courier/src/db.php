<?php
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple Mock Database using Session to persist changes during browser usage
if (!isset($_SESSION['shipments'])) {
    $_SESSION['shipments'] = [
        [
            'tracking_no' => 'SJ-8294719-PK',
            'sender' => 'Ali Khan',
            'receiver' => 'Zainab Ahmed',
            'origin' => 'Karachi',
            'destination' => 'Lahore',
            'weight' => '1.5 kg',
            'status' => 'delivered',
            'date' => '2026-05-20',
            'amount' => 450,
            'payment_type' => 'COD',
            'history' => [
                ['time' => '2026-05-20 16:30', 'status' => 'delivered', 'desc' => 'Shipment delivered to Zainab Ahmed'],
                ['time' => '2026-05-20 09:15', 'status' => 'out_for_delivery', 'desc' => 'Shipment is out for delivery with courier'],
                ['time' => '2026-05-19 14:20', 'status' => 'in_transit', 'desc' => 'Shipment departed from Karachi Hub to Lahore Hub'],
                ['time' => '2026-05-18 11:00', 'status' => 'picked_up', 'desc' => 'Shipment picked up by courier'],
            ]
        ],
        [
            'tracking_no' => 'SJ-1049582-PK',
            'sender' => 'Farhan Sheikh',
            'receiver' => 'Maria Yousuf',
            'origin' => 'Islamabad',
            'destination' => 'Karachi',
            'weight' => '0.5 kg',
            'status' => 'in_transit',
            'date' => '2026-05-21',
            'amount' => 300,
            'payment_type' => 'Prepaid',
            'history' => [
                ['time' => '2026-05-22 08:30', 'status' => 'in_transit', 'desc' => 'Arrived at Karachi Sorting Facility'],
                ['time' => '2026-05-21 15:45', 'status' => 'picked_up', 'desc' => 'Shipment picked up from sender in Islamabad'],
            ]
        ],
        [
            'tracking_no' => 'SJ-3850183-PK',
            'sender' => 'Muhammad Bilal',
            'receiver' => 'Ayesha Sana',
            'origin' => 'Peshawar',
            'destination' => 'Rawalpindi',
            'weight' => '3.2 kg',
            'status' => 'pending',
            'date' => '2026-05-22',
            'amount' => 850,
            'payment_type' => 'COD',
            'history' => [
                ['time' => '2026-05-22 11:00', 'status' => 'pending', 'desc' => 'Shipment booking created successfully'],
            ]
        ],
        [
            'tracking_no' => 'SJ-9948201-PK',
            'sender' => 'Zara Fashion',
            'receiver' => 'Hamza Ali',
            'origin' => 'Lahore',
            'destination' => 'Faisalabad',
            'weight' => '2.0 kg',
            'status' => 'out_for_delivery',
            'date' => '2026-05-21',
            'amount' => 600,
            'payment_type' => 'COD',
            'history' => [
                ['time' => '2026-05-22 10:00', 'status' => 'out_for_delivery', 'desc' => 'Out for delivery. Courier: Fahad (0300-1234567)'],
                ['time' => '2026-05-21 18:20', 'status' => 'in_transit', 'desc' => 'Arrived at Faisalabad Sorting Hub'],
                ['time' => '2026-05-21 10:15', 'status' => 'picked_up', 'desc' => 'Shipment picked up from warehouse'],
            ]
        ],
    ];
}

// User Accounts
$users = [
    [
        'username' => 'admin',
        'password' => 'admin123',
        'name' => 'Shah Jee Administrator',
        'role' => 'Administrator'
    ],
    [
        'username' => 'shahjee',
        'password' => 'courier2026',
        'name' => 'Shah Jee Operator',
        'role' => 'Operator'
    ]
];

// Helper Functions
function check_login($username, $password) {
    global $users;
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $_SESSION['user'] = $user;
            return true;
        }
    }
    return false;
}

function get_shipments() {
    return $_SESSION['shipments'];
}

function get_shipment_by_tracking($tracking_no) {
    foreach ($_SESSION['shipments'] as $shipment) {
        if (strtoupper($shipment['tracking_no']) === strtoupper(trim($tracking_no))) {
            return $shipment;
        }
    }
    return null;
}

function create_shipment($sender, $receiver, $origin, $destination, $weight, $amount, $payment_type) {
    $tracking_no = 'SJ-' . rand(1000000, 9999999) . '-PK';
    $new_shipment = [
        'tracking_no' => $tracking_no,
        'sender' => $sender,
        'receiver' => $receiver,
        'origin' => $origin,
        'destination' => $destination,
        'weight' => $weight,
        'status' => 'pending',
        'date' => date('Y-m-d'),
        'amount' => intval($amount),
        'payment_type' => $payment_type,
        'history' => [
            ['time' => date('Y-m-d H:i'), 'status' => 'pending', 'desc' => 'Shipment booking created successfully'],
        ]
    ];
    array_unshift($_SESSION['shipments'], $new_shipment);
    return $tracking_no;
}
