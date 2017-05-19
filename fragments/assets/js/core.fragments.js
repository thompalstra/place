var body = document.body;
var document = document;

/**
* @var a [string|array|object|function]
*/
var fragments = function(a){

    // find the current variable (a), or execute auto-functions
    switch(typeof a){
        /**
        * @return the current element(s) based on a css selector query
        */
        case 'string':
            a = a.trim();
            if(a.charAt(0) == '<'){
                wrapper = document.createElement('div');
                wrapper.innerHTML = a;

                return wrapper.firstChild;
            } else {
                return fragments.findByString(a);
            }

        break;
        /**
        * @return the current object as an element type
        */
        case 'object':
            if(a.nodeType == 1){
                // dom element
                return fragments.findByObject(a);
            } else if (a.nodeType == 9){
                // document
                return fragments.findByObject(a);
            }
            throw new Error("Object is not of type Node");
        break;
        // converts a NodeList into an element
        case 'array':
            return new _element(a);
        break;
        // executes a function
        case 'function':
            return a();
        break;
    }
    // for performance reasons, fragments will ONLY allow a single xhr to be executed
    this.xhr;
}
// reserves variable f
var f = fragments;

/**
* main construct class for fragments-element which extends the default functionality of a normal DOMNode
* @param Array elements [NodeList]
*/
var _element = function(elements){
    // constructs the current collection based on the constructor arguments
    for(iConstruct=0; iConstruct < elements.length; iConstruct++){
        this[iConstruct] = elements[iConstruct];
    }
    // sets the current length to be equal to the constructed collection's length
    this.length = elements.length;
    // adds the given className to the (first) element
    this.addClass = function(className){
        return this[0].classList.add(className);
    }
    // removes the given className on the (first) element
    this.removeClass = function(className){
        return this[0].classList.remove(className);
    }
    // toggles the given className on the (first) element
    this.toggleClass = function(className){
        if(this.hasClass(className)){
            return this.removeClass(className);
        } else {
            return this.addClass(className);
        }
    }
    this.hasClass = function(className){
        return (this[0].className.indexOf(className) !== -1) ? true : false;
    }
    /**
    * removes the (first) element
    */
    this.remove = function(){
        this[0].remove();
    }
    /**
    * @return the first element in the current collection
    */
    this.element = function(){
        return this[0];
    }
    /**
    * @return all elements in the current collection
    */
    this.elements = function(){
        r = [];
        for(iElements=0; iElements < this.length; iElements++){
            r[iElements] = this[iElements];
        }
        return r;
    }
    /**
    * @return the (first) element's parent
    */
    this.parent = function(){
        return new _element(this[0].parentNode);
    }
    /**
    * @returns element matching the given query or object
    */
    this.parentsUntil = function(search){
        parentsUntilNode = this[0];
        while(parentsUntilNode !== null && parentsUntilNode.nodeType !== 9){
            if(typeof search == 'string'){
                if(parentsUntilNode.matches(search)){
                    return parentsUntilNode;
                }
            } else if (typeof search == 'object'){
                if(parentsUntilNode == search){
                    return parentsUntilNode;
                }
            }
            parentsUntilNode = parentsUntilNode.parentNode;
        }
        return false;
    }
    /**
    * @return the (first) element's children
    */
    this.children = function(query){
        if(query != undefined){
            return new _element(this[0].querySelectorAll(query));
        } else {
            return new _element(this[0].childNodes);
        }
    }
    this.appendChild = function(element){
        return this[0].appendChild(element);
    }
    /**
    * @return the (first) element's previous sibling
    */
    this.previous = function(){
        return this[0].previousSibling;
    }
    /**
    * @return the next sibling
    */
    this.next = function(){
        return this[0].nextSibling;
    }

    /**
    * sets the current style key if there is a value
    *
    * @param String key
    * @param String value
    * @return the current style value if there is no value
    */
    this.style = function(key, value){
        if(value == undefined){
            if(this[0].style[key] == undefined){
                return this[0].style[key];
            } else {
                return window.getComputedStyle(this[0])[key];
            }
        } else {
            return this[0].style[key] = value;
        }
    }
    /**
    *  @return check if the element actually hosts a collection of items
    */
    this.exists = function(){
        return (this.length > 0) ? true : false;
    }
    /**
    * sets the current attribute key when a value is specified
    *
    * @param String key
    * @param String value
    * @return the current attribute value when no value is specified
    */
    this.attr = function(key, value){
        if(value == undefined){
            return this[0].getAttribute(key);
        } else {
            return this[0].setAttribute(key, value);
        }
    }
    /**
    * sets the current value when a value is specified
    *
    * @param String value
    * @return the current value when no value is specified
    */
    this.value = function(value = undefined){
        if(value === undefined){
            return this[0].value;
        } else {
            return this[0].value = value;
        }
    }
    /**
    * loops through each element and call the callback function upon the current element
    * @param Function callback
    */
    this.each = function(callback){
        for(var iEach = 0; iEach < this.length; iEach++){
            callback.call(this[iEach]);
        }
    }
    /**
    * @returns element, accepts a css selector string variable to perform matching based on the selector
    * @param String query [css selector]
    */
    this.siblings = function(query = undefined){
        a = [];
        children = this[0].parentNode.childNodes;
        source = this[0];
        for(var iSiblings in children) {
            if(children[iSiblings].nodeType == 1 && children[iSiblings] !== source){
                if(query !== undefined && children[iSiblings].matches(query)){
                    a.push(children[iSiblings]);
                } else if(query === undefined) {
                    a.push(children[iSiblings]);
                }
            }
        }
        return new _element(a);
    }
    /**
    * @param String query [css selector]
    * @return all child elements matching the given css query
    */
    this.find = function(query){
        return new _element(this[0].querySelectorAll(query));
    }
    /**
    * @param String query [css selector]
    * @return a single child element matching the given css query
    */
    this.findOne = function(query){
        return new _element([this[0].querySelector(query)]);
    }
    this.findAll = function(query){
        return new _element(this[0].querySelectorAll(query));
    }

    /**
    * sets event handlers to the current element(s)
    * when setting an event on the DOCUMENT type, it will delegate them
    *
    *   @param String eventType [click|hover|keydown|etc...]
    *   @param Function callback
    *   @param Boolean bubbles [allow an event to bubble or not]
    *
    * when delegated
    * callback is the current query selector
    * bubbles is the current callback
    * bubblesDelegate is the bubbles setter
    *
    *   @param String eventType ['click'|'hover'|'keydown'|etc...]
    *   @param String callback [css selector]
    *   @param Function bubbles
    *   @param boolean bubblesDelegate
    */
    this.on = function(eventType, callback, bubbles, bubblesDelegate = true){
        if(!this.exists()){return;}
        if(this[0].nodeType == 9){
            document.addEventListener(eventType, function(e){
                var delegateStop = true,
                    delegateCount = 0,
                    selected = document.querySelectorAll(callback);

                for(;selected[delegateCount];){
                    el = selected[delegateCount];
                    if(e.target == this){
                        return bubbles.call(e.target, e);
                   } else if(bubblesDelegate == true){
                       if(n = fragments(e.target).parentsUntil(callback)){
                           return bubbles.call(n, e);
                       }
                   }
                   delegateCount++;
                }
            }, false);
        } else {
            if(bubbles == undefined){
                bubble = true;
            }
            for(iOn=0;iOn<this.length;iOn++){
                this[iOn].addEventListener(eventType, callback, bubbles);
            }
        }
    }
    this.trigger = function(eventType){
        return this[0].dispatchEvent(new Event(eventType, {
            'bubbles': true,
            })
        );
    }

    // sets the elements in the collection to display none
    this.hide = function(){
        for(iHide=0;iHide<this.length;iHide++){
            this[iHide].style.display = 'none';
        }
    }
    // sets the elements in the collection to display an inherit value
    this.show = function(){
        for(iShow=0;iShow<this.length;iShow++){
            this[iShow].style.display = 'block';
        }
    }
    // sets the elements in the collection to display when display none, or to display none when display
    this.toggle = function(){
        for(iToggle=0;iToggle<this.length;iToggle++){
            this[iToggle].style.display = (this[iToggle].style.display == 'none') ? '' : 'none';
        }
    }
    /**
    * @return boolean based on if the (first) element is checked
    */
    this.checked = function(){
        return this[0].checked;
    }

    this.slideUp = function(time, animation){
        time = (time == undefined) ? 1000 : time;
        animation = (animation == undefined) ? 'linear' : 'ease-in-out';


        el = this[0];

        targetheight = window.getComputedStyle(el)['height'];

        el.style.transition = "height " + animation + " " + (time / 1000) + "s";
        el.style.height = targetheight;
        el.style.overflow = 'hidden';

        setTimeout(function(e){
            el.style.height = '0px';

        }, 1);
    }
    this.slideDown = function(time, animation){
        time = (time == undefined) ? 1000 : time;
        animation = (animation == undefined) ? 'linear' : 'ease-in-out';


        el = this[0];

        currentHeight = window.getComputedStyle(el)['height'];

        el.style.display = '';
        el.style.height = '';
        el.style.transition = '';
        el.style.overflow = 'hidden';

        targetheight = window.getComputedStyle(el)['height'];

        el.style.transition = "height " + animation + " " + (time / 1000) + "s";
        el.style.height = currentHeight;

        setTimeout(function(e){
            el.style.height = targetheight;
        }, 1);
    }

    this.slideToggle = function(time, animation){
        if(target[0].style.height == '0px'){
            target.slideDown(time, animation);
        } else {
            target.slideUp(time, animation);
        }
    }
}


var core = {
    /**
    *   @return element based on the given query string
    *   @param String string [css selector]
    */
    findByString: function(string){
        return new _element(document.querySelectorAll(string));
    },
    /**
    *  @return element from a single object
    */
    findByObject: function(object){
        return new _element([object]);
    },

    stringify: function(object){
        return JSON.stringify(object);
    },
}
var requests = {
    // the request collection, housing multiple ajax functions such as xhr validation and submission
    request: {
        // validates the supplied object file to make sure all required variables are set accordingly
        validate: function(obj){
            if(obj == undefined){
                obj = {};
            }
            if(!obj.hasOwnProperty('method')){
                obj.method = 'get';
            }
            if(!obj.hasOwnProperty('url')){
                obj.url = location.href;
            }
            if(!obj.hasOwnProperty('type')){
                obj.type = 'document';
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
            if(!obj.hasOwnProperty('headers')){
                obj.headers = [];
            }

            obj.headers["X-Requested-With"] = "XMLHttpRequest";
            if(obj.method.toLowerCase() == 'post'){
                obj.headers["Content-type"] = "application/x-www-form-urlencoded";
            }
            // if(obj.type.toLowerCase() == 'json'){
            //     obj.headers["Content-type"] = "application/json";
            // }


            return obj;
        },
        /**
        * sends an ajax request based on the obj's settings (by default defined in fragments.request.validate)
        * @param Object obj {url:'url', method:'get|post|put', data:[object], done: [function], error: [function], headers: [array]}
        */
        send: function(obj){
            obj = f.request.validate(obj);

            if(fragments.xhr !== undefined){
                fragments.xhr.abort();

            }
            fragments.xhr = new XMLHttpRequest();


            fragments.xhr.open(obj.method, obj.url, true);
            fragments.xhr.responseType = obj.type;
            fragments.xhr.type = obj.type;

            for(var iHeader in obj.headers){
                fragments.xhr.setRequestHeader(iHeader, obj.headers[iHeader]);
            }

            fragments.xhr.onreadystatechange = function(e){
                if(this.readyState == 4){
                    obj.done(this.response, this);
                }
            }

            fragments.xhr.onerror = function(e){
                obj.error(this.response, this)
            }
            //alert(obj.type.toLowerCase());
            if(obj.method.toLowerCase() == 'post'){
                //s = fragments.stringify(obj.data);
                //console.log(s);
                s = "";
                r = [];
                for(var i in obj.data){


                    if(typeof obj.data[i] == 'object'){
                        r.push(i+"="+JSON.stringify(obj.data[i]));
                    } else {
                        r.push(i+"="+obj.data[i]);
                    }


                }
                s = r.join('&');
                fragments.xhr.send(s);
            } else {
                fragments.xhr.send(obj.data);
            }

        },
    },
    /**
    * makes a request to the (given) url, and replaces the current element with the element from the request's response
    */
    reload: function(obj){
        // sets a dummy callback when no callback is defined
        callback = (obj.hasOwnProperty('done') ? obj.done : function(){})
        if(!obj.hasOwnProperty('container')){
            throw new Error('Missing required parameter: container');
        }
        obj.type = 'document';
        obj.done = function(response, xhr){
            if(response.nodeType == 9 || response.nodeType == 1){
                // attempt to find the new _element
                newEl = response.querySelector(obj.container);
                oldEl = document.querySelector(obj.container);

                parent = oldEl.parentNode;
                parent.replaceChild(newEl, oldEl);
                callback(response, xhr);
            }
        }
        obj.error = function(){
            throw new Error('Unable to complete the request');
        }
        fragments.request.send(obj);
    }
};
// collection of data events handlers that ease functionality for fragments users
var dataEvents = function(){
    fragments(document).on('click', '[toggle="true"]', function(e){
        e.stopPropagation();
        attr = fragments(this).attr('toggle-for');
        if(attr){
            target = fragments(attr);
        } else {
            target = fragments(this);
        }
        if(target.exists()){
            if(target.attr('toggled') == 'true'){
                target.attr('toggled', 'false');
            } else {
                target.attr('toggled', 'true');
            }
        }
    }, true);
}
/**
* extension that adds (or executes) the supplied collection of functions or events
* @param Function|object arg
*/
fragments.extend = function(arg){
    type = typeof arg;
    if(type === 'object'){
        for(var iExtend in arg){
            f[iExtend] = arg[iExtend];
        }
    } else if(type === 'function') {
        arg();
    }

}
// the currently selected collections to be loaded into fragments
fragments.extend(core);
fragments.extend(requests);
fragments.extend(dataEvents);
