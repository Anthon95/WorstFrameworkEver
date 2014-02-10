

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// WFE CLASS 
// 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function WFE(toLoad, config) {
    
    
    var config = config;
    var uriHandler;
    var data = {
        routes: {}
    };

    if (history.pushState) {
        uriHandler = new PushHandler();
    }
    else {
        uriHandler = new HashHandler();
    }
    
    uriHandler.change('ok');
    
    ////////////////////////////////////////////////////////////////////////////////// PUBLIC

    if (typeof WFE.initialized === "undefined") {

        WFE.initialized = true;
        WFE.instance = this;

        ////////////////////////////////////////////////////////////////////////////////// WFE : load

        WFE.prototype.load = function() {
            
            
        };

    }
    else {
        return WFE.instance;
    }
    
    return this;
}

////////////////////////////////////////////////////////////////////////////////// STATIC
    
WFE.getInstance = function() {
    return WFE.instance;
};


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// WFEEvent CLASS 
// 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function WFEEvent(name) {
    
    if(typeof name === 'undefined' || isWFEEvent(name)) {
        jQuery.error(name + ' is not a valid WFEEvent name');
    }

    var name = name;
    
    var event = document.createEvent("Event");
    event.initEvent(name, true, true);

    if (typeof WFEEvent.initialized === "undefined") {

        WFEEvent.initialized = true;

        ////////////////////////////////////////////////////////////////////////////////// WFEEvent : binData

        WFEEvent.prototype.bindData = function(key, value) {
            
            if(typeof key === 'undefined') {
                $.error('key param is not defined when binding data to ' + name + ' event');
            }
            if(typeof value === 'undefined') {
                $.error('value param is not defined when binding data to ' + name + ' event');
            }
            
            event[key] = value;
        };
        
        ////////////////////////////////////////////////////////////////////////////////// WFEEvent : trigger

        WFEEvent.prototype.trigger = function() {

            window.dispatchEvent(event);
        };
    }
    
    ////////////////////////////////////////////////////////////////////////////////////// PRIVATE
    
    function isWFEEvent(event) {
        for(index in this.events) {
            if(events[index] === event) {
                return true;
            }
        }
        return false;
    }
    
    return this;
}

///////////////////////////////////////////////////////////////////////////////////////// STATIC

WFEEvent.events = {
    LOADING : 'loading'
};


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// PushHandler CLASS 
// 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function PushHandler() {

    this.name = "push";

    if (typeof PushHandler.initialized === "undefined") {

        PushHandler.initialized = true;

        ////////////////////////////////////////////////////////////////////////////////// PUSH : CHANGE

        PushHandler.prototype.change = function(uri) {


            state = uri;
            if (state === '') {
                state = '/' + config.appRoute;
            }

            state = state.replace('//', '/');console.log(state);

            window.history.pushState("", uri, state);
            //changePage(uri);
        };

        ////////////////////////////////////////////////////////////////////////////////// PUSH : GET_CURRENT

        PushHandler.prototype.getCurrent = function() {

            if (settings.baseUrl.indexOf('http://') !== -1) {
                href = window.location.href.replace(settings.baseUrl, '');
            }
            else {
                href = window.location.pathname.replace(settings.baseUrl, '');
            }


            return removeFirstSlash(href.replace('//', '/'));
        };

        ////////////////////////////////////////////////////////////////////////////////// PUSH : START

        PushHandler.prototype.start = function() {
            window.onpopstate = this.onPopState;
            $('.' + settings.navLinkClass).bind('click', onNavClickHandler);
        };

        ////////////////////////////////////////////////////////////////////////////////// PUSH : STOP

        PushHandler.prototype.stop = function() {
            window.onpopstate = null;
            $('.' + settings.navLinkClass).unbind('click', onNavClickHandler);
        };

        ///////////////////////////////////////////////////////////////////////////////// PUSH : FUNCTIONS

        PushHandler.prototype.onPopState = function() {

            if (!ajaxNavigation.data.firstLoad) {
                changePage(ajaxNavigation.data.uriHandler.getCurrent());
            }

        };
    }
    
    return this;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// HashHandler CLASS 
// 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function HashHandler() {

    this.name = "hash";

    if (typeof PushHandler.initialized === "undefined") {

        HashHandler.initialized = true;

        ////////////////////////////////////////////////////////////////////////////////// HASH : CHANGE

        HashHandler.prototype.change = function(uri) {
            window.location.hash = uri;
        };

        ////////////////////////////////////////////////////////////////////////////////// HASH : GET_CURRENT

        HashHandler.prototype.getCurrent = function() {
            return window.location.hash.replace('#', '');
        };

        ////////////////////////////////////////////////////////////////////////////////// HASH : START

        HashHandler.prototype.start = function() {

            // event supported
            if ("onhashchange" in window) {
                this.getCurrent();
                window.onhashchange = function() {
                    changePage(ajaxNavigation.data.uriHandler.getCurrent());
                };
            }
            // event not supported
            else {
                ajaxNavigation.data.storedHash = window.location.hash;
                ajaxNavigation.data.interval = window.setInterval(function() {
                    if (window.location.hash !== ajaxNavigation.data.storedHash) {
                        ajaxNavigation.data.storedHash = window.location.hash;
                        changePage(ajaxNavigation.data.uriHandler.getCurrent());
                    }
                }, 100);
            }

            $('.' + settings.navLinkClass).bind('click', onNavClickHandler);
        };

        ////////////////////////////////////////////////////////////////////////////////// HASH : STOP

        HashHandler.prototype.stop = function() {

            // event supported
            if ("onhashchange" in window) {
                window.onhashchange = null;
            }
            // event not supported
            else {
                clearInterval(ajaxNavigation.data.interval);
            }

            $('.' + settings.navLinkClass).unbind('click', onNavClickHandler);
        };
    }
    
    return this;
}
