// function RadioStream(){
//     this.audio = f('#radioco-radioplayer');
//
//
//     this.playButton = f('.btn-player.play');
//     this.pauseButton = f('.btn-player.pause');
//     this.volumeIndicator = f('.progress-player > .progress-value');
//     this.volumeClicked = false;
//
//     this.play = function(e){
//         src = this.audio.findOne('source').attr('src');
//         this.audio.attr('src',src);
//         this.audio.attr('autoplay','true');
//
//         this.pauseButton.show();
//         this.playButton.hide();
//     }
//     this.pause = function(e){
//         this.audio.attr('src','');
//         this.audio.attr('autoplay','false');
//
//         this.pauseButton.hide();
//         this.playButton.show();
//     }
//     this.toggle = function(e){
//
//     }
//
//     this.setVolume = function(e){
//         el = f('.progress-player');
//         value = e.offsetX/ parseInt(el.style('width')) * 100;
//         this.volumeIndicator.style('width', value+"%");
//         this.audio[0].volume = value / 100;
//     }
// }
//
// player = new RadioStream();
//
// f(document).on('click', '.btn-player.play', function(e){
//     player.play();
// });
// f(document).on('click', '.btn-player.pause', function(e){
//     player.pause();
// });
//
// f(document).on('click', '.progress-player', function(e){
//     if(e.which == 1){
//         player.setVolume(e);
//     }
//
// });
//
// f('.progress-player').on('mousedown', function(e){
//     if(e.which == 1){
//         player.volumeClicked = true;
//     }
//
// });
// f('.progress-player').on('mouseup', function(e){
//     if(e.which == 1){
//         player.volumeClicked = false;
//     }
//
// });
// f('.progress-player').on('mousemove', function(e){
//     if(player.volumeClicked == true){
//         player.setVolume(e);
//     }
// });
volTimeout = null;
f(document).on('input', '.radioco-volume', function(e){
    val = this.value;
    if(val < 25){
        added = '<i class="material-icons">volume_mute</i>';
    } else if(val < 50){
        added = '<i class="material-icons">volume_down</i>';
    } else {
        added = '<i class="material-icons">volume_up</i>';
    }
    f('.radioplayer > .volume-indicator').element().innerHTML = added + " " + val + "%";
    if(volTimeout != null){
        clearTimeout(volTimeout);
    }
    volTimeout = setTimeout(function(e){
        f('.radioplayer > .volume-indicator').attr('out', 'true');
    }, 2000);
    f('.radioplayer > .volume-indicator').attr('out', 'false');

});

var player = $('.radioplayer').radiocoPlayer();
player.event('audioPlay', function(event){
    // alert("");
    //perform action when audio is played here
});
body.onload = function(e){
    f('.radioplayer').addClass('out');
    setTimeout(function(e){
        f('.radioplayer').removeClass('loading');
    }, 100);
}
