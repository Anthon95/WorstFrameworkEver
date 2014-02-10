

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// WFE CLASS 
// 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function WFE_API(toLoad, _config) {


    var config = _config;
    var uriHandler;
    var data = {
        routes: {}
    };

    ////////////////////////////////////////////////////////////////////////////////// PUBLIC

    if (typeof WFE_API.initialized === "undefined") {

        WFE_API.initialized = true;

        ////////////////////////////////////////////////////////////////////////////////// WFE : loadRoute

        WFE_API.prototype.loadRoute = function(routeName, params, method) {
            
            if (typeof method === 'undefined') {
                method = 'GET';
            }
            if (typeof params === 'undefined') {
                params = {};
            }
            
            jQuery.ajax({
                type:method,
                url: config.appRoot + '/',
                data: {'routeParams': params, routeName:routeName}
            }).done(function(data) {
                
                jQuery('#page-content').html(data);
                //uriHandler.change( getUriFromUrl(jQuery('#page-data #route-data').attr('path')) );
            });
            
        };

        ////////////////////////////////////////////////////////////////////////////////// WFE : loadURL

        WFE_API.prototype.loadURL = function(url, params) {

            if(typeof params === 'undefined') {
                params = {};
            }

            jQuery.ajax({
                url: url,
                data: {'routeParams': params}
            }).done(function(data) {

                jQuery('#page-content').html(data);
            });

        };

        ////////////////////////////////////////////////////////////////////////////////// WFE : navigate

        WFE_API.prototype.navigate = function(routeName, params) {
            
            
        };
    }

    ///////////////////////////////////////////////////////////////////////////////// PRIVATE

    function getUriFromUrl(url) {
        return url.replace(config.appRoot, '');
    }

    ///////////////////////////////////////////////////////////////////////////////// CONSTRUCTOR

    if (typeof window.history.pushState !== 'undefined') {
        uriHandler = new PushHandler(config.appRoot, config.wfeLinkClass);
    }
    else {
        uriHandler = new HashHandler(config.appRoot, config.wfeLinkClass);
    }

    uriHandler.start();

    //this.loadURL(toLoad);

    return this;
}





//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// WFEEvent CLASS 
// 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function WFEEvent(name) {

    if (typeof name === 'undefined' || isWFEEvent(name)) {
        jQuery.error(name + ' is not a valid WFEEvent name');
    }

    var name = name;

    var event = document.createEvent("Event");
    event.initEvent(name, true, true);

    if (typeof WFEEvent.initialized === "undefined") {

        WFEEvent.initialized = true;

        ////////////////////////////////////////////////////////////////////////////////// WFEEvent : binData

        WFEEvent.prototype.bindData = function(key, value) {

            if (typeof key === 'undefined') {
                $.error('key param is not defined when binding data to ' + name + ' event');
            }
            if (typeof value === 'undefined') {
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
        for (index in this.events) {
            if (events[index] === event) {
                return true;
            }
        }
        return false;
    }

    return this;
}

///////////////////////////////////////////////////////////////////////////////////////// STATIC

WFEEvent.events = {
    LOADING: 'loading'
};


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// PushHandler CLASS 
// 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function PushHandler(baseURL, wfeLinkClass) {

    this.name = "push";
    this.baseUrl = baseURL;
    this.wfeLinkClass = wfeLinkClass;

    if (typeof PushHandler.initialized === "undefined") {

        PushHandler.initialized = true;

        ////////////////////////////////////////////////////////////////////////////////// PUSH : CHANGE

        PushHandler.prototype.change = function(uri) {

            state = this.baseUrl + uri;

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
            //$('.' + this.wfeLinkClass).bind('click', onNavClickHandler);
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

function HashHandler(baseURL, wfeLinkClass) {

    this.name = "hash";
    this.baseURL = baseURL;
    this.wfeLinkClass = wfeLinkClass;

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
