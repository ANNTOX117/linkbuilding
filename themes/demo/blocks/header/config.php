<?php

return [
    "title"    => "Nav bar",
    "category" => "Layout",
    "icon"     => "fa fa-object-group",
    "logo" => [
        "type"  => "image",
        "label" => "Logo image",
        "value" => phpb_theme_asset('img/logo.png'),
    ],
    "settings" => [
        "url_1" => [
            "type"  => "text",
            "label" => "First URL",
            "value" => "/services",
        ],
        "text_1" => [
            "type"  => "text",
            "label" => "Text",
            "value" => "Services",
        ],
        "url_2" => [
            "type"  => "text",
            "label" => "Second URL",
            "value" => "/aboutus",
        ],
        "text_2" => [
            "type"  => "text",
            "label" => "Text",
            "value" => "About us",
        ],
        "url_3" => [
            "type"  => "text",
            "label" => "Third URL",
            "value" => "/contact",
        ],
        "text_3" => [
            "type"  => "text",
            "label" => "Text",
            "value" => "Contact",
        ]
    ]
];
