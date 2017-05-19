<?php
namespace fragments\web;

use fragments\web\Asset;

class FragAsset extends Asset{
    public $css = [
        '/fragments/assets/css/core.fragments.css',
        '/fragments/assets/css/widgets.fragments.css',
        'https://fonts.googleapis.com/css?family=Lato|Open+Sans|Roboto',
    ];
    public $js = [
        '/fragments/assets/js/core.fragments.js',
        '/fragments/assets/js/widgets.fragments.js',
    ];

}

?>
