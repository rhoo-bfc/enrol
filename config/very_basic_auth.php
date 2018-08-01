<?php

    /**
     * Configuration for the "HTTP Very Basic Auth"-middleware
     */
    return [
        // Username
        'user'              => 'admin',

        // Password
        'password'          => 'U2NRhKWI',

        // Environments where the middleware is active
        'envs'              => [
            'dev',
            'development',
            'staging',
            'production',
            'prod',
            'testing'
        ],

        // Message to display if the user "opts out"/clicks "cancel"
        'error_message'     => 'You have to supply your credentials to access '
        . 'this resource.'
    ];
