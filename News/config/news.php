<?php
return [
    'section' => [
        'default' => [
            'content' => [
                'title' => 'Content',
                /* 'blade' => 'breadcrump', */
                // 'static' => true,
                'fields' => [
                    'content' => ['validation' => 'required', 'type' => 'editor']//,'allowed' => 'news'
                ]
            ],
        ],
    ]
];
?>