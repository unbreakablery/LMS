<?php

return  [
    'roles' => [
        '0' => 'Removed User',
        '1' => 'Administrator',
        '2' => 'Staff',
        '3' => 'Student'
    ],
    'permissions' => [
        'manage-user' => ['1'],
        'manage-category' => ['1', '2'],
        'manage-equipment' => ['1', '2'],
        'equipment' => ['3'],
        'manage-booking' => ['1', '2'],
        'booking' => ['3'],
        'tracking' => ['1', '2'],
        'manage-notification' => ['1'],
        'notification' => ['2', '3']
    ]
];