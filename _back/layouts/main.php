<?php
use fragments\web\FragAsset;
use back\assets\BackAsset;

use fragments\widgets\Nav;

$fragAsset = new FragAsset();
$frontAsset = new BackAsset();
$this->registerAsset($frontAsset);
$this->registerAsset($fragAsset);

$items = [
    [
        'label' => '<i class="icon material-icons pull-left">home</i> home',
        'url' => '/',
    ],
    [
        'label' => '<i class="icon material-icons pull-left">code</i> blog',
        'items' => [
            [
                'label' => 'all',
                'url' => '/blog/index',
            ],
        ],
    ],
    [
        'label' => '<i class="icon material-icons pull-left">insert_drive_file</i> files',
        'url' => '/files/index',
    ]
];
?>
<html>
    <head>
        <?=$this->head()?>
    </head>
    <body>
        <section class='header'>
            <div class='container'>
            <?=Nav::widget([
                'items' => $items,
                'options' => [
                    'class' => 'nav nav-default align-left',
                ],
            ])?>
            </div>
        </section>
        <section class='container'>
            <?=$content?>
        </section>
        <?=$this->footer()?>
    </body>
</html>
