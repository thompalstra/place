_('.nav.nav-item').on('click', function(e){
    if(this.parentNode.tagName == 'A' || e.target.parentNode.parentNode.tagName == 'A'){
        return true;
    }
    e.stopPropagation();
    e.preventDefault();

    ul = _(this).findOne('ul');
    if(ul.exists()){
        ul.slideToggle(300);
    }
    _(this).toggleClass('open');

    _(this).siblings().each(function(e){
        ul = _(this).findOne('ul');
        if(ul.exists()){
            ul.hide();
            _(this).removeClass('open');
        }
    })


}, false);

_('.slide.slide-default').on('click', function(e){
    e.preventDefault();
});

_('.slide-container .next-slide').on('click', function(e){
    innerContainer = this.parentNode.querySelector('.inner-container');

    active = this.parentNode.querySelector('.active-slide');
    activeParent = active.parentNode;

    nextParent = activeParent.nextSibling;

    if(nextParent == null){
        nextParent = innerContainer.childNodes[0];
    }

    next = nextParent.querySelector('.slide');

    _(active).removeClass('active-slide');
    _(next).addClass('active-slide');

    index = Array.from(innerContainer.children).indexOf(nextParent);
    innerContainer.style.left = "-"+(100 * index)+"%";

    progress = this.parentNode.querySelector('.progress');
    slideSpeed = this.parentNode.getAttribute('data-slide');
    if(progress){
        dtName = this.parentNode.getAttribute('data-timeout');
        progress.style.transition = '';
        progress.style.width = '0%';
        progress.style.opacity = '0';

        if(window[dtName] != undefined){
            clearTimeout(window[dtName]);
            progress.style.transition = '';
            progress.style.width = '0%';
            progress.style.opacity = '0';
        }

        window[dtName] = setTimeout(function(e){
            progress.style.transition = progress.getAttribute('data-transition');
            progress.style.width = '100%';
            progress.style.opacity = '1';
        },slideSpeed);
    }
});

_('.slide-container > .previous-slide').on('click', function(e){
    innerContainer = this.parentNode.querySelector('.inner-container');

    active = this.parentNode.querySelector('.active-slide');
    activeParent = active.parentNode;

    previousParent = activeParent.previousSibling;

    if(previousParent == null){
        previousParent = innerContainer.childNodes[innerContainer.childNodes.length -1];
        // previous must be the first element
    }

    previous = previousParent.querySelector('.slide');

    _(active).removeClass('active-slide');
    _(previous).addClass('active-slide');

    index = Array.from(innerContainer.children).indexOf(previousParent);
    innerContainer.style.left = "-"+(100 * index)+"%";

    progress = this.parentNode.querySelector('.progress');
    slideSpeed = this.parentNode.getAttribute('data-slide');
    if(progress){
        dtName = this.parentNode.getAttribute('data-timeout');
        progress.style.transition = '';
        progress.style.width = '0%';
        progress.style.opacity = '0';

        if(window[dtName] != undefined){
            clearTimeout(window[dtName]);
            progress.style.transition = '';
            progress.style.width = '0%';
            progress.style.opacity = '0';
        }

        window[dtName] = setTimeout(function(e){
            progress.style.transition = progress.getAttribute('data-transition');
            progress.style.width = '100%';
            progress.style.opacity = '1';
        },slideSpeed);
    }
});

_('.slide-container').on('swipeleft', function(e){
    this.querySelector('.previous-slide').dispatchEvent(new Event('click', {
        'view' : 'window',
        'bubbles': true,
        'cancelable': true,
    }));
});
_('.slide-container').on('swiperight', function(e){
    this.querySelector('.next-slide').dispatchEvent(new Event('click', {
        'view' : 'window',
        'bubbles': true,
        'cancelable': true,
    }));
});


_.pointer = {
    'down' : false,
    'startX' : 0,
    'startY' : 0,
    'x' : 0,
    'y' : 0,
    'prevented': false,
};
_(document).on('touchstart', '*', function(e){
    _.pointer.down = true;
    _.pointer.startX = e.touches[0].pageX;
    _.pointer.startY = e.touches[0].pageY;
    _.pointer.x = e.touches[0].pageX;
    _.pointer.y = e.touches[0].pageY;
    _.pointer.prevented = false;

});
_(document).on('touchmove', '*', function(e){
    if(_.pointer.prevented == false && _.pointer.down == true){
        _.pointer.x = e.touches[e.touches.length -1].pageX;
        _.pointer.y = e.touches[e.touches.length -1].pageY;
    }
});
_(document).on('touchend', '*', function(e){
    diffX = _.pointer.x - _.pointer.startX;
    diffY = _.pointer.y - _.pointer.startY;
    if(diffX < -20){
        _(this).dispatch(_.events.swiperight);
    }
    else if(diffX > 20){
         _(this).dispatch(_.events.swipeleft);
    }

    if(diffY < -20){
        _(this).dispatch(_.events.swipebottom);
    } else if(diffY > 20){

        _(this).dispatch(_.events.swipetop);
    }
    _.pointer.down = false;
    _.pointer.startX = 0;
    _.pointer.startY = 0;
    _.pointer.x = 0;
    _.pointer.y = 0;
    _.pointer.prevented = false;
});


_(document).on('click', '[data-pagination-infinite="true"]', function(e){
    e.preventDefault();
    href = _(this.parentNode).attr('href');
    targetId = _(this.parentNode.parentNode).attr('id');
    insertBefore = this.parentNode;
    parent = this.parentNode.parentNode;
    _.send({
        url: href,
        method: 'get',
        type: 'document',
        done: function(response){
            target = response.querySelector('#'+targetId);
            if(target){
                _(target).children().each(function(e){
                    clone = this.cloneNode(true);
                    _(parent).appendChild(clone);
                    if(_(clone).data('pagination-end') == 'true'){
                        console.log('end');
                    }
                    else if(clone.tagName !== 'A'){
                        clone.style.opacity = '0';
                        clone.style.animation = 'fade-in 1s forwards';
                    }


                });
            }
            insertBefore.remove();
        },
        error: function(error){
            console.log(error);
        }
    });
});

_(document).on('change', 'input[class="row-select"]', function(e){
    selected = _('input[class="row-select"]:checked');
    if(selected[0].length > 0){
        _('.select-all')[0][0].checked = true;
        _('.row-select-action').show();
    } else {
        _('.select-all')[0][0].checked = false;
        _('.row-select-action').hide();
    }
});
_(document).on('click', '.row-select', function(e){
    alert("");
})
_(document).on('click', '.row-select-action > a', function(e){
    e.preventDefault();
    c = confirm(_(this).data('message'));
    href = _(this).attr('href');
    if(c){
        send(href);
    }
});
_(document).on('change', '.select-all', function(e){
    checked = this.checked;
    i = (checked) ? _('.row-select-action').show() : _('.row-select-action').hide();
    _('input[class="row-select"]').each(function(e){
        this.checked = checked;
    });
});

function send(href){
    selected = _('input[class="row-select"]:checked');
    collection = [];
    selected.each(function(e){
        collection.push(this.value);
    });
    href += "?collection="+JSON.stringify(collection);
    _.send({
        method: 'GET',
        url: href,
        done: function(response){
            if(response.result == true){
                _.reload({
                    container: '.table.table-default',
                    done: function(e){
                        for(var select in collection){
                            input = _('input[value="'+collection[select]+'"].row-select');
                            if(input.exists()){
                                input[0][0].checked = true;
                            }
                        }
                    }
                });
            }
        }
    })
}
_(document).on('click', '.table.table-default > tbody > tr > td', function(e){
    p = this.parentNode;
    select = _(p).findOne('.row-select');
    if(select.exists()){
        select[0][0].checked = !select[0][0].checked;
        selected = _('input[class="row-select"]:checked');
        if(selected[0].length > 0){
            _('.select-all')[0][0].checked = true;
            _('.row-select-action').show();
        } else {
            _('.select-all')[0][0].checked = false;
            _('.row-select-action').hide();
        }
    }
});
