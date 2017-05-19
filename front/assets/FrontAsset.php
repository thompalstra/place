<?php
namespace front\assets;

use fragments\web\Asset;

class FrontAsset extends Asset{
    public $css = [
        'https://fonts.googleapis.com/icon?family=Material+Icons',
        'https://afeld.github.io/emoji-css/emoji.css',
        '/front/web/css/struct.css',
        '/common/web/css/common.css',
    ];
    public $js = [
        'http://code.jquery.com/jquery-latest.min.js',
        'https://public.radio.co/playerapi/jquery.radiocoplayer.min.js',
        '/front/web/js/struct.js',
    ];

}

?>
