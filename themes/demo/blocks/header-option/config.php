<?php

return [
    "title"    => "Header One",
    "category" => "Layout",
    "icon"     => "fa fa-object-group",
    "settings" => [
        "header_title" => [
            "type"  => "text",
            "label" => "Title",
            "value" => "What does it offer?",
        ],
        "paragraph" => [
            "type"  => "text",
            "label" => "Paragraph",
            "value" => "Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem."
        ],
        "image" => [
            "type"  => "image",
            "label" => "Image",
            "value" => phpb_theme_asset('img/image-1.jpg'),
        ],
    ]
];
