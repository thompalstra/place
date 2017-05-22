<?php
use fragments\web\FragAsset;
use front\assets\FrontAsset;
use common\models\Blog;
use fragments\widgets\Nav;
$this->registerAsset(new FragAsset());
$this->registerAsset(new FrontAsset());
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no" />
        <?=$this->head()?>
    </head>
    <body class='dark2'>
        <section class='hidden'>
            <audio id="radioco-radioplayer" preload="none">
                <source src="http://stream.radio.co/s79388a0b8/listen" type="audio/mpeg">Your browser does not support the audio element.
            </audio>
        </section>
        <!-- <section class='header'>
            <div class='inner'>
            </div>
        </section> -->
        <section class='header-small'>
            <div class='container'>
                <img class='logo' src="/web/uploads/img/place_logo.png">
            </div>
        </section>
        <section class='container'>
            <?=$content?>
        </section>
        <?=$this->footer()?>
    </body>
</html>
