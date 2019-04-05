<?php

require_once 'HTML/require.php';

use HTML\Builder;
use HTML\Element;
use HTML\Fragment;

echo (new Builder())
    ->addStylesheets([
        "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css",
    ])
    ->addScripts([
        "https://code.jquery.com/jquery-3.3.1.slim.min.js",
        "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js",
        "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js",
    ], true)
    ->addElements([
        Element::Div(null, [
            Element::H1(null, [new Fragment('Hello World')]),
            Element::Button(['class' => 'btn btn-primary'], [new Fragment('click on me !')]),
        ]),
    ])
    ->build();
