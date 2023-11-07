<?php

return array(
    'users' => [
        'addedOn' => 'Added On',
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'usertype' => 'User Type',
        'client' => 'Client List',
        'mobile' => 'Mobile',
        'gender' => 'Gender',
        'profileInfo' => 'Profile Info',
        'active' => 'Active',
        'activeNote' => 'Active Account',
        'inactive' => 'Inactive Account',
    ],
    'clients' => [
        'addedOn' => 'Added On',
        'clientName' => 'Client Name',
        'emailNote' => 'Note: This email will be used for login',
        'generateRandomPassword' => 'Generate Random Password',
        'companyName' => 'Company Name',
        'companyDetails' => 'Company Details',
        'website' => 'Official Website',
        'companyEmail' => 'Official Email',
        'officePhoneNumber' => 'Official Phone Number',
        'companyLogo' => 'Company Logo',
        'address' => 'Company Address',
        'appName' => 'App Name',
        'clientInfo' => 'Client Info'
    ],
    'profile' => [
        'profilePicture' => 'Profile Picture',
        'gender' => 'Gender',
        'wallet' => 'Wallet',
        'booking' => 'Booking',
        'transaction' => 'Transaction'
    ],
    'accountSettings' => [
        'changeLanguage' => 'Change Language',
        'companyAddress' => 'Company Address',
        'language' => 'Language',
        'appLanguageInfo' => "Application's default language"
    ],
    'settings' => [
        'settings' => 'Settings',
        'menu' => [
            'app' => [
                'menu' => 'System Settings',
                'general' => 'General',
            ],
            'profile' => [
                'menu' => 'Profile Settings',
                'profile' => 'Profile'
            ],
            'site-group' => [
                'menu' => 'Site Group',
                'site-group' => 'Site Group'
            ],
            'pusher' => [
                'menu' => 'Pusher Settings',
                'pusher' => 'Pusher'
            ]
        ],
        'dateFormat' => 'Date Format',
        'timeFormat' => 'Time Format',
        'defaultTimezone' => 'Default Timezone',
        'language' => 'Language',
        'pusher_app_id' => 'Pusher App ID',
        'pusher_app_key' => 'Pusher App Key',
        'pusher_app_secret' => 'Pusher App Secret',
        'pusher_app_cluster' => 'Pusher App Cluster',
        'is2FA' => 'Authentication OTP',
        'is2FANote' => 'Enable/Disable OTP Authentication for login',
        'statusSiteGroup' => 'Status Site Group',
        'allowGuestRegistration' => 'Allow Guest Registration',
        'public' => 'Public',
        'private' => 'Private',
        'allowSignup' => 'Allow Signup',
        'notAllowSignup' => 'Disable Allow Signup',
    ],
    'lockers' => [
        'title' => 'Lockers',
        'create' => 'Create Locker',
        'details' => 'Locker Details',
        'code' => 'Locker Code',
        'description' => 'Description',
        'image' => 'Image',
        'dateOfManufacture' => 'Date of Manufacture',
        'status' => 'Status',
        'warrantyDuration' => 'Warranty Duration',
        'placeholders' => [
            'code' => 'e.g. LOCKER001',
            'description' => 'e.g. Locker on 1st floor, near the entrance',
            'dateOfManufacture' => 'e.g. 2020-01-01',
            'warrantyDuration' => 'e.g. 2',
        ],
        'tabs' => [
            'details' => 'Details',
            'slots' => 'Slots',
            'bulkCreate' => 'Bulk Create Slots',
        ],
        'lockerPicture' => 'Locker Picture',
    ],
    'locations' => [
        'title' => 'Locations',
        'code' => 'Location Code',
        'description' => 'Description',
        'client' => 'Client',
        'type' => 'Location Type',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'placeholders' => [
            'code' => 'e.g. LAB001',
            'description' => 'e.g. Laboratory on 1st floor, near the entrance',
            'latitude' => 'e.g. 3.1390',
            'longitude' => 'e.g. 101.6869',
        ],
    ],
    'locationTypes' => [
        'title' => 'Location Types',
    ],
    'paymentMethod' => [
        'title' => 'Payment Methods',
        'create' => 'Create Payment Method',
        'details' => 'Payment Method Details',
        'configs' => 'Payment Method Configs',
        'code' => 'Payment Method Code',
        'name' => 'Payment Method Name',
        'type' => 'Payment Method Type',
        'placeholders' => [
            'code' => 'e.g. ZALOPAY001',
            'name' => 'e.g. ZaLoPay',
        ],
        'configFieldset' => [
            'information' => 'Information',
            'terminal_id' => 'Terminal ID',
            'secret_key' => 'Secret Key',
        ]
    ]
);
