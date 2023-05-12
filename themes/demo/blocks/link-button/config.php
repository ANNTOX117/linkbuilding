<?php

return [
    "title"    => "Link button",
    "category" => "Button",
    "icon"     => "fa fa-button",
    "settings" => [
        "text" => [
            "type"  => "text",
            "label" => "Text",
            "value" => "Button",
        ],
        "url" => [
            "type"  => "text",
            "label" => "URL",
            "value" => "",
        ],
        "primary" => [
            "type"  => "checkbox",
            "label" => "Primary",
            "value" => true,
        ],
        "is_login" => [
            "type"  => "checkbox",
            "label" => "Login button ?",
            "value" => false,
        ],
        "is_registration" => [
            "type"  => "checkbox",
            "label" => "Registration button ?",
            "value" => false,
        ],
    ]
];
