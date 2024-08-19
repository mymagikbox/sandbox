<?php
return [
    'user' => [
        // auth
        'auth' => [
            'username' => [
                'not_blank' => 'Field "Username" is blank',
            ],
            'password' => [
                'not_blank' => 'Field "password" is blank',
            ],
        ],
        // common
        'id' => [
            'not_blank' => 'Username ID is blank',
        ],
        'username' => [
            'not_blank' => 'Username is blank',
            'not_correct' => 'Username not correct',
        ],
        'email' => [
            'not_blank' => 'Email is blank',
            'not_correct' => 'Email not correct',
        ],
        'role' => [
            'not_blank' => 'Role is blank',
            'not_valid' => 'Role is not valid',
            'admin' => 'Admin',
            'manager' => 'Manager',
        ],
        'status' => [
            'unconfirmed' => 'Unconfirmed',
            'active' => 'Enabled',
            'deleted' => 'Deleted',
        ],
        'password' => [
            'not_blank' => 'Password is blank',
        ],
        'exception' => [
            'already.exist' => 'User with this username already exist',
            'not.found' => 'User not found',
            'username.or.password.invalid' => 'User or password incorrect',
            'invalid.token' => 'Incorrect token',
            'missing.token' => 'Missing token',
            'incorrect.auth.number' => 'The number of incorrect authorizations has been exceeded, please check back later',
            'cant.delete.itself' => 'Can not delete itself',
        ]
    ]
];