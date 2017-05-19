var _ElBase = {
    setClass: function(className){
        for(i = 0; i < this[0].length; i++){
            this[0][i].className = className;
        }
    },
    addClass: function(className){
        for(i = 0; i < this[0].length; i++){
            this[0][i].classList.add(className);
        }
    },
    removeClass: function(className){
        for(i = 0; i < this[0].length; i++){
            this[0][i].classList.remove(className);
        }
    },
    hasClass: function(className){
        return (this[0][0].classList.contains(className));
    },
    toggleClass: function(className){
        for(i = 0; i < this[0].length; i++){
            if(_(this[0][i]).hasClass(className)){
                this[0][i].classList.remove(className);
            } else {
                this[0][i].classList.add(className);
            }
        }
    },
    attr: function(key, value){
        if(value != undefined){
            this[0][0].setAttribute(key, value);
        }
        return this[0][0].getAttribute(key);
    },
    text: function(key){
            if(key !== undefined){
                this[0][0].innerText = key;
            } else {
                return this[0][0].innerText;
            }
    },
    data: function(key, value){
        if(key && value){
            return this[0][0].setAttribute("data-"+key, value);
        }
        return this[0][0].getAttribute("data-"+key);
    },
    val: function(a){
        if(a !== undefined){
            return this[0][0].value = a;
        } else {
            return this[0][0].value;
        }

    },
    matches: function(query){
        if(this[0][0] == undefined || this[0][0] == document){
            return false;
        }
        return (this[0][0].matches(query));

    },
    parent: function(){
        return _(this[0][0].parentNode);
    },
    parentsUntil: function(q){
        function up(e){
            if(e == undefined || e == document){
                return new _Element([]);
            }
            if(e.matches(q)){
                return e;
            } else {
                return up(e.parent());
            }
        }
        r = up(this.parent());
        return r;
    },
    siblings: function(e){
        siblings = [];
        source = this[0][0];
        [].forEach.call(this[0][0].parentNode.children, function(el) {
            if(e !== undefined){
                if(el.matches(e)){
                    siblings.push(el);
                }
            } else {
                if(el != source){
                    siblings.push(el);
                }
            }

        });
        return new _Element(siblings);
    },
    children: function(a){
        if(a !== undefined){
            return new _Element(this[0][0].querySelectorAll(a));
        } else {
            return new _Element(this[0][0].childNodes);
        }

    },
    findOne: function(name){
        return new _Element([this[0][0].querySelector(name)]);
    },
    findAll: function(name){
        return new _Element(this[0][0].querySelectorAll(name));
    },
    css: function(obj){
        for(i in obj){
            this[0][0].style[i] = obj[i];
        }
    },
    style: function(key, value){
        if(value !== undefined){
            return this[0][0].style[key] = value;
        } else {
            inlineValue = this[0][0].style[key];
            if(!inlineValue){
                return window.getComputedStyle(this[0][0])[key];
            } else {
                return inlineValue;
            }
        }
    },
    each: function(f){
        [].forEach.call(this[0], function(el) {
            f.call(el);
        });
    },
    trigger: function(a){
        this[0][0].dispatchEvent(new Event(a));
    },
    remove: function(){
        this[0][0].remove();
    },
    on: function(a, b, c, d){
        //check if the event should be delegated
        if(this[0][0] === document){
            // a = event, b = selector, c = callback, d = bubbles
            d = (d == undefined) ? true : d;
            document.addEventListener(a, function(e){
                [].forEach.call(document.querySelectorAll(b), function(element, i, arr){
                //document.querySelectorAll(b).forEach(function(element){
                    if(e.target == element){
                        c.call(element, e);
                    }
                });
            });
        } else {
            // a = event, b = callback, c = bubbles
            for (var i = 0; i < this[0].length; ++i) {
                c = (c == undefined) ? true : c;
                this[0][i].addEventListener(a, b, c);
            }
        }
    },
    toFormData: function(){
        return new FormData(this[0][0]);
    },
    serialize: function(e){
        r = "";
        for (var [key, value] of this.toFormData().entries()) {
            r += encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&";
        }
        return r.substring(0, r.length -1);

    },
    html: function(a){
        if(a){
            this[0][0].innerHTML = a;
        }
        else{
            return this[0][0].innerHTML;
        }
    },
    exists: function(){
        return (this[0][0] !== undefined && this[0][0] !== null);
    },
    next: function(a){
        if(a === undefined){
            return new _Element([this[0][0].nextSibling]);
        } else {
            parent = this[0][0].parentNode;
            children = parent.childNodes;
        }

    },
    nextVisible: function(node){
        if(node.exists() && node.next().exists()){
            if(node.next().style('display') == 'none'){
                return getNextVisible(node.next());
            }
            return node.next();
        } else {
            return new _Element([undefined]);
        }
    },
    previousVisible:function(node){
        if(node.exists() && node.previous().exists()){
            if(node.previous().style('display') == 'none'){
                return getPreviousVisible(node.previous());
            }
            return node.previous();
        } else {
            return new _Element([undefined]);
        }
    },
    previous: function(){
        return new _Element([this[0][0].previousSibling]);
    },
    first: function(){
        return new _Element([this[0][0]]);
    },
    firstElement: function(){
            return this[0][0];
    },
    last: function(){
        return new _Element([this[0][this[0].length-1]]);
    },
    lastElement: function(){
        return this[0][this[0].length-1];
    },
    focus: function(){
        this[0][0].focus();
    },
    show: function(){
        for (var i = 0; i < this[0].length; ++i) {
            this[0][i].style.display = 'block';
        }
    },
    hide: function(){
        for (var i = 0; i < this[0].length; ++i) {
            this[0][i].style.display = 'none';
        }
    },
    toggle: function(){
        for (var i = 0; i < this[0].length; ++i) {
            if(window.getComputedStyle(this[0][i]).display === 'none'){
                _(this[0][i]).show();
            } else {
                _(this[0][i]).hide();
            }
        }
    },
    appendChild: function(a){
        return this[0][0].appendChild(a);
    },
    dispatch: function(event){
        return this[0][0].dispatchEvent(event(this[0][0]));
    },
    index: function(event){
        return Array.prototype.indexOf.call(this[0][0].parentNode.childNodes, this[0][0]);
    },
    slideDown: function(time){
        c = this[0][0];
        slideDownElement = this[0][0];
        slideDownElement.style.display = 'block';
        slideDownElement.style.height = 'auto';
        h = window.getComputedStyle(c)['height'];
        slideDownElement.style.display = 'none';
        slideDownElement.style['transition'] = (time / 1000) + 's height linear';
        slideDownElement.style.overflow = 'hidden';
        slideDownElement.style.display = 'block';
        slideDownElement.style.height = '0px';
        // slideDownElement.style.overflow = 'hidden';
        setTimeout(function(e){
            slideDownElement.style.height = h;
        },1);
        setTimeout(function(e){
            slideDownElement.style.height = '';
        }, time + 1);
    },
    slideUp: function(time){
        slideUpElement = this[0][0];

        slideUpElement.style.display = 'block';
        h = window.getComputedStyle(slideUpElement)['height'];
        slideUpElement.style.height = h;
        slideUpElement.style.overflow = 'hidden';
        slideUpElement.style['transition'] = (time / 1000) + 's height linear';
        setTimeout(function(e){
            slideUpElement.style.height = '0px';
        },1);
        setTimeout(function(e){
            slideUpElement.style.display = '';
            slideUpElement.style.height = '0px';
            slideUpElement.style['transition'] = '';
            slideUpElement.style.display = 'none';
        }, time);
    },
    slideToggle: function(time){
        c = this[0][0];
        if(parseInt(window.getComputedStyle(c)['height']) > 0){
            this.slideUp(time);
        } else {
            this.slideDown(time);
        }
    }
};
var _Base = {
    validateXhr: function(obj){
        if(!obj.hasOwnProperty('method')){
            obj.method = "GET";
        }
        if(!obj.hasOwnProperty('type')){
            obj.type = 'json';
        }
        if(!obj.hasOwnProperty('url')){
            obj.url = location.href;
        }
        if(!obj.hasOwnProperty('data')){
            obj.data = '';
        }
        if(!obj.hasOwnProperty('done')){
            obj.done = function(){}
        }
        if(!obj.hasOwnProperty('error')){
            obj.error = function(){}
        }
        return obj;
    },
    send: function send(obj){
        obj = this.validateXhr(obj);

        xhr = new XMLHttpRequest();
        xhr.open(obj.method, obj.url);
        if(obj.type){
            xhr.responseType = obj.type;
        }
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        if(obj.method.toLowerCase() == 'post'){
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        }
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4){
                if(xhr.status === 200){
                    obj.done(xhr.response, xhr);
                } else {
                    obj.error(xhr.response, xhr)
                }
            }
        }
        xhr.send(obj.data);
    },
    reload: function reload(obj){
        if(obj.hasOwnProperty('done')){
            callback = obj.done;
        } else {
            callback = function(){}
        }

        obj.type = 'document';
        obj.done = function(response){
            _new = response.querySelector(obj.container);
            _old = document.querySelector(obj.container);
            parent = _old.parentNode;
            parent.replaceChild(_new, _old);
            callback();
        }
        obj.error = function(){
            throw "Could not find element in response.";
        }
        this.send(obj);
    },
    history: {
            push: function(url){
                history.pushState(null, null, url);
            }
    },
};
var _Ajax = {
    ajax: {
        form: function form(obj){
            if(!obj.form){
                return;
            }
            if(obj.done === 'undefined'){

            }
            _(document).on('submit', obj.form, function(e){
                e.preventDefault();
                eForm = _(obj.form);
                _.send({
                    method: eForm.attr('method'),
                    url: eForm.attr('action'),
                    data: eForm.toFormData(),
                    source: obj.form,
                    target: obj.form,
                    done:function(response, xhr){
                        if(location.href != xhr.responseURL){
                            location.href = xhr.responseURL;
                        }
                    }
                });
            });

        },
    },
};
function paneCollection(){
    this.panes = [];
    this.toolbar = _('#toolbar');
    this.toolbarOrientation = '';
    this.actions = {
        toolbar: {
            closeAll: function(){
                w = window;
                w.panes.panes.forEach(function(p){
                    if(p != undefined){
                        r = p.close();
                        if(r == true){

                        } else {
                            throw new Error('Something went wrong!');
                        }
                    }
                });
            },
            top: function(){
                //window.panes.toolbar.removeClass('top');
                //window.panes.toolbar.removeClass('left');
                window.panes.toolbar.removeClass('bottom');
                //window.panes.toolbar.removeClass('right');

                window.panes.toolbar.addClass('right');
                window.panes.toolbar.addClass('left');
                window.panes.toolbar.addClass('top');

                window.panes.toolbarOrientation = 'top';
            },
            bottom: function(){
                window.panes.toolbar.removeClass('top');
                //window.panes.toolbar.removeClass('left');
                //window.panes.toolbar.removeClass('bottom');
                //window.panes.toolbar.removeClass('right');

                window.panes.toolbar.addClass('right');
                window.panes.toolbar.addClass('left');
                window.panes.toolbar.addClass('bottom');

                window.panes.toolbarOrientation = 'bottom';
            },
            left: function(){
                //window.panes.toolbar.removeClass('top');
                //window.panes.toolbar.removeClass('left');
                //window.panes.toolbar.removeClass('bottom');
                window.panes.toolbar.removeClass('right');

                window.panes.toolbar.addClass('top');
                window.panes.toolbar.addClass('left');
                window.panes.toolbar.addClass('bottom');

                window.panes.toolbarOrientation = 'left';
            },
            right: function(){
                //window.panes.toolbar.removeClass('top');
                window.panes.toolbar.removeClass('left');
                //window.panes.toolbar.removeClass('bottom');
                //window.panes.toolbar.removeClass('right');

                window.panes.toolbar.addClass('top');
                window.panes.toolbar.addClass('right');
                window.panes.toolbar.addClass('bottom');

                window.panes.toolbarOrientation = 'right';
            },

        }
    }


    this.setToolbarOrientation = function setToolbarOrientation(){
        str = this.toolbar[0][0].classList.value;
        if(str.indexOf('bottom') !== -1 && str.indexOf('left') !== -1 && str.indexOf('right') !== -1){
            this.toolbarOrientation = 'bottom';
        } else if(str.indexOf('top') !== -1 && str.indexOf('left') !== -1 && str.indexOf('right') !== -1){
            this.toolbarOrientation = 'top';
        } else if(str.indexOf('top') !== -1 && str.indexOf('left') !== -1 && str.indexOf('bottom') !== -1){
            this.toolbarOrientation = 'left';
        } else if(str.indexOf('top') !== -1 && str.indexOf('right') !== -1 && str.indexOf('bottom') !== -1){
            this.toolbarOrientation = 'left';
        } else {
            throw new Error("Incorrect configuration! " + arguments.callee.name);
        }
    }
    this.getMaximizedAttributes = function(){
        switch(window.panes.toolbarOrientation){
            case 'bottom':
            return {
                top: 0,
                left: 0,
                width: '100%',
                height: "calc(100% - "+window.panes.toolbar.style('height')+")",
            }
            case 'top':
            return {
                top: window.panes.toolbar.style('height'),
                left: 0,
                width: "100%",
                height: "calc(100% - "+window.panes.toolbar.style('height')+")",
            }
            default:

            return;
        }
    }


    this.setToolbarOrientation();

    this.activePane = undefined;
    this.add = function(pane){
        index = "pane-" + this.panes.length;
        this.panes[index] = pane;
        this.panes.length++;
        return index;
    }
    this.find = function(query){
        return this.panes[query];
    }
    this.setFocus = function(pane){
        _('.pane').each(function(e){
            _(this).removeClass('focus');
        });
        _(pane.pane).addClass('focus');
        id = _(pane.pane).attr('id');
        query = '[pane-target="'+id+'"]';
        toolbarItem = window.panes.toolbar.findOne(query);
        this.setActiveToolbarItem(toolbarItem);
    }
    this.unsetFocus = function(){
        _('.pane').each(function(e){
            _(this).removeClass('focus');
        });
        this.toolbar.findAll('.toolbar-item').removeClass('active');
    }
    this.setActive = function(pane){
        this.activePane = pane;
    }
    this.getActive = function(){
        return this.activePane;
    }
    this.unsetActive = function(){
        this.activePane = undefined;
    }
    this.setActiveToolbarItem = function(item){
        item.siblings().each(function(e){
            _(this).removeClass('active');
        });
        item.addClass('active');
    }
    this.unsetActiveToolbaritem = function(){
        this.toolbar.findAll('.toolbar-item').removeClass('active');
    }
}
function Pane(target){
    this.pane = target;
    this.mouseX = 0;
    this.mouseY = 0;
    this.scriptId = undefined;
    this.previousLayout = {
        top : 0,
        bottom: 0,
        left: 0,
        right: 0,
        x: 0,
        y: 0,
    }
    this.maximized = false;
    this.minimize = function(){
        _(this.pane).hide();
        window.panes.unsetActiveToolbaritem();
    }
    this.maximize = function(){
        panel = _(this.pane);
        if(this.maximized != true){
            this.previousLayout = {
                top: panel.style('top'),
                left: panel.style('left'),
                width: panel[0][0].style.width,
                height: panel[0][0].style.height,
            }

            attr = window.panes.getMaximizedAttributes();

            panel.css(attr);
            window.panes.setFocus(this);
            this.maximized = true;
        } else {
            panel.css(this.previousLayout);
            this.maximized = false;
        }

    }
    this.refresh = function(url){
        url = (url == undefined) ? _(this.pane).data('src') : url;
        this.reload({
            method: 'GET',
            url: url,
        });
    }
    this.closeContext = function(){
        _(this.pane).findOne('.header-context').hide();
    }
    this.close = function(){
        id = _(this.pane).attr('id');
        query = '[pane-target="'+id+'"]';
        toolbarItem = window.panes.toolbar.findOne(query);
        toolbarItem[0][0].remove();

        window.panes.panes[this.pane.getAttribute('id')] = undefined;
        _(this.pane)[0][0].remove();
        return true;
    }
    this.show = function(){
        _(this.pane).show();
    }
    this.reload = function(obj){
        p = this.pane;

        body = _(p).findOne('.body');
        scrollOffset = (body.exists() ? { left: body[0][0].scrollLeft, top: body[0][0].scrollTop } : undefined);
        // if(body.exists()){
        //     scrollOffset = { left: body[0][0].scrollLeft, top: body[0][0].scrollTop };
        // } else {
        //
        // }
        var xhr = new XMLHttpRequest();
        xhr.open(obj.method, obj.url);
        xhr.responseType = "document";
        xhr.onreadystatechange = function(){
            if(xhr.status == 200 && xhr.readyState == 4){

                var header = _(xhr.response).findOne('.pane > .header')[0][0];
                var body = _(xhr.response).findOne('.pane > .body')[0][0];

                if(header != null){
                    _(p).findOne('.header')[0][0].remove();
                    _(p).appendChild(header);
                }

                if(body != null){
                    _(p).findOne('.body')[0][0].remove();
                    _(p).appendChild(body);
                    if(scrollOffset != undefined){
                        body.scrollLeft = scrollOffset.left;
                        body.scrollTop = scrollOffset.top;
                    }
                }

                if(header == null && body == null){
                    var msg = 'Could not update pane. ';
                    msg += (header == null) ? "Missing header. " : '';
                    msg += (body == null) ? "Missing body. " : '';
                    console.log(msg);
                }
            }
        }
        if(obj.data){
            xhr.send(obj.data);
        } else {
            xhr.send();
        }
    }
}
var _Events = {
        events: {
                beforeopen: function (source){
                    return new Event('beforeopen', {
                        'view' : 'window',
                        'bubbles': true,
                        'cancelable': true,
                    });
                },
                afteropen: function (source){
                    return new Event('afteropen', {
                        'view' : 'window',
                        'bubbles': true,
                        'cancelable': true,
                    });
                },
                beforeselect: function (source){
                    return new Event('beforeselect', {
                        'view' : 'window',
                        'bubbles': true,
                        'cancelable': true,
                    });
                },
                afterselect: function (source){
                    return new Event('afterselect', {
                        'view' : 'window',
                        'bubbles': true,
                        'cancelable': true,
                    });
                },
                swipeleft: function(source){
                    return new Event('swipeleft', {
                        'view' : 'window',
                        'bubbles': true,
                        'cancelable': true,
                    });
                },
                swiperight: function(source){
                    return new Event('swiperight', {
                        'view' : 'window',
                        'bubbles': true,
                        'cancelable': true,
                    });
                },
                swipetop: function(source){
                    return new Event('swipetop', {
                        'view' : 'window',
                        'bubbles': true,
                        'cancelable': true,
                    });
                },
                swipebottom: function(source){
                    return new Event('swipebottom', {
                        'view' : 'window',
                        'bubbles': true,
                        'cancelable': true,
                    });
                }
        },
};
var _Element = function(el){
    this[0] = el;
}
_Element.prototype = _ElBase;
var _ = function(a, b){
    if(a){
        if(typeof a === "string"){
            if(a.charAt(0) == '[' && a.charAt(a.length-1) == ']'){
                return new _Element(document.querySelectorAll(a));
            } else if(a.charAt(0) == '<') {
                wrapper = document.createElement('div');
                wrapper.innerHTML = a;
                return wrapper.firstChild;
            } else {
                return new _Element(document.querySelectorAll(a));
            }
        }
        if(typeof a === "object"){
            return new _Element([a]);
        }
    }
}
var _ExtendedEvents = function(){
    _('[toggle-for]').on('click', function(e){
        t = this.getAttribute('toggle-for');
        _(t).each(function(e){
            if(this.getAttribute('is-toggled') == 'true'){
                this.setAttribute('is-toggled', 'false');
            } else {
                this.setAttribute('is-toggled', 'true');
            }
        });
    });
    _('[toggle-style="true"][toggle-trigger="hover"]').on('mouseenter', function(e){
        toggleStyle(this, e);
    });
    _('[toggle-style="true"][toggle-trigger="hover"]').on('mouseleave', function(e){
        toggleStyle(this, e);
    });
    _('[toggle-style="true"][toggle-trigger="click"]').on('click', function(e){
        toggleStyle(this, e);
    });

    function toggleStyle(el, e){
        e.stopPropagation();
        from = el.getAttribute('style-from');
        to = el.getAttribute('style-to');
        if(!from || !to){
            message = "";
            if(!from){
                message += "from ";
            }
            if(!to){
                message += "to ";
            }
            throw new Error("Required attributes missing: "+message);
        }
        fromSplit = from.split(':');
        toSplit = to.split(':');

        fromKey = fromSplit[0];
        fromValue = fromSplit[1];
        toKey = toSplit[0];
        toValue = toSplit[1];

        if(toValue == '100%'){
            pk = window.getComputedStyle(el.parentNode)[toKey];
            if(pk){
                toValue = pk;
            }
        }
        if(toValue == 'inherit'){
            toValue = '';
        }
        if(fromValue == '100%'){
            pk = window.getComputedStyle(el.parentNode)[toKey];
            if(pk){
                fromValue = pk;
            }
        }
        if(fromValue = 'inherit'){
            fromValue = '';
        }

        keyValue = _(el).style(toKey);
        if(keyValue !== toValue){
             _(el).style(toKey, toValue);
        } else {
            _(el).style(fromKey, fromValue);
        }
    }
}
_.extend = function(args){
    if(typeof args == 'function'){
        args();
    } else if(typeof args == 'object') {
        for(var f in args){
            this[f] = args[f];
        }
    }

}
_.extend(_Base);
_.extend(_Events);
_.extend(_Ajax);
_.extend(_ExtendedEvents);
