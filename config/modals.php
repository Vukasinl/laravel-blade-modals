<?php

use Vukasinl\Modals\Components;

return [

    'asset_url' => null,

    // here you can extend or add new modal components
    // prefix to every component is 'modals'
    'modals' => [
        'form-modal' => Components\FormModal::class,
    ]
];