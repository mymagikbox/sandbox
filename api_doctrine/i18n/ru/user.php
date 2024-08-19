<?php
return [
    'user' => [
        // auth
        'auth' => [
            'username' => [
                'not_blank' => 'Поле "Пользователь" не заполнено',
            ],
            'password' => [
                'not_blank' => 'Поле "Пароль" не заполнено',
            ],
        ],
        // common
        'id' => [
            'not_blank' => 'ID пользователя не заполнено',
            'not_correct' => 'ID пользователя указано не верно',
        ],
        'username' => [
            'not_blank' => 'Имя пользователя не заполнено',
            'not_correct' => 'Имя пользователя указано не верно',
        ],
        'email' => [
            'not_blank' => 'Email не заполнен',
            'not_correct' => 'Email указан не верно',
        ],
        'role' => [
            'not_blank' => 'Роль пользователя не заполнена',
            'not_valid' => 'Роль пользователя не верная',
            'admin' => 'Админ',
            'manager' => 'Менеджер',
        ],
        'status' => [
            'unconfirmed' => 'Не подтвержден',
            'active' => 'Включен',
            'deleted' => 'Удален',
        ],
        'password' => [
            'not_blank' => 'Поле "Пароль" не заполнено',
        ],
        'exception' => [
            'already.exist' => 'Пользователь с таким Email уже существует',
            'not.found' => 'Пользователь не найден',
            'username.or.password.invalid' => 'Имя пользователя или пароль неправильные',
            'invalid.token' => 'Неправильный токен',
            'missing.token' => 'Токен отсутсвует',
            'incorrect.auth.number' => 'Превышено число неправильных авторизаций, зайдите позже',
            'cant.delete.itself' => 'Нельзя удалить самого себя',
        ]
    ]
];