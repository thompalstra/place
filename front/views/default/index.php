<?php
?>

<section class='content chat col dt4 tb6 mb12'>
    <?=$chatClient->ui()?>
</section>

<section class='content player col dt8 tb6 mb12'>
    <!-- <div class='player-controls'>
        <span class='btn-player play'><i class="material-icons">play_arrow</i></span>
        <span class='btn-player pause' style='display: none'><i class="material-icons">pause</i></span>
        <span class='progress-player'>
            <span class='progress-back'></span>
            <span class='progress-value'></span>
        </span>
    </div> -->
    <div
        class="radioplayer loading"
        data-src="http://stream.radio.co/s79388a0b8/listen"
        data-autoplay="false"
        data-playbutton="true"
        data-volumeslider="true"
        data-elapsedtime="true"
        data-nowplaying="true"
        data-showplayer="true"
        data-volume="50"
        data-showartwork="true">
        <div class='volume-indicator'>0%</div>
        <div class='loader'><img src="/web/uploads/img/spinner.gif"></div>
    </div>
</section>
<section class='content highlights col dt8 tb6 mb12' style=''>
    <div class='content col dt3 dt3 mb3'>
        <article>
            <img style='width:100%' src="https://placeholdit.imgix.net/~text?txtsize=28&bg=0099ff&txtclr=ffffff&txt=300%C3%97300&w=300&h=300">
            This is a piece text about a specific topic.
        </article>
    </div>
    <div class='content col dt3 dt3 mb3'>
        <article>
            <img style='width:100%' src="https://placeholdit.imgix.net/~text?txtsize=28&txt=300%C3%97300&w=300&h=300">
            Want to request a song? Reach out to us!
        </article>
    </div>
    <div class='content col dt3 dt3 mb3'>
        <article>
            <img style='width:100%' src="http://style.anu.edu.au/_anu/4/images/placeholders/person.png">
            Latest songs
        </article>
    </div>
    <div class='content col dt3 dt3 mb3'>
        <article>
            <img style='width:100%' src="http://www.rotaryhardenberg.nl/img/avatar_placeholder.png">
            About our tracks and tracklists
        </article>
    </div>
</section>
<section class='content information col dt4 tb12 mb12'>
    <h4>Information</h4>
    <p>Every monday to friday we're LIVE, providing all the hottest beats and songs.</p>
</section>
