<?php
namespace back\assets;

use fragments\web\Asset;

class BackAsset extends Asset{
    public $css = [
        'https://fonts.googleapis.com/icon?family=Material+Icons',
        '/back/web/css/struct.css',
        '/common/web/css/common.css',
    ];
    public $js = [
        '/back/web/js/struct.js',
    ];

}

?>
