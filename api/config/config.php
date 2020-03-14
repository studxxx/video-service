<?php
declare(strict_types=1);

use Api\Http\Action;

return [
    'settings' => [
        'addContentLengthHeader' => false,
        'displayErrorDetails' => (bool)getenv('API_DEBUG'),
    ],

    Action\HomeAction::class => function () {
        return new Action\HomeAction();
    },
];
