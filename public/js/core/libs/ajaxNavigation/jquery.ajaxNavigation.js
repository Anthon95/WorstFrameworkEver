// AJAX NAVIGATION /////////////////////////////////////////
    
(function($) {
    
    // data
    var ajaxNavigation = {

        // INTERNAL DATA
        data: {
            cssLoaded: {},
            jsLoaded: {},
            uriHandler: null,
            init:false,
            navigateCallback: false,
            loadNextFromCache: true,
            firstLoad:true,
            storedHash: false,
            interval: false,
            prevPage: false,
            currentPage: '',
            urlLoaded: {},
            urlLoading: {},
            started: false,
            contents: {},
            titles: {},
            css: {},
            js: {}
        },

        // API
        navigateTo: navigateTo,
        init: initNavigation,
        start: startAjaxNavigation,
        stop: stopAjaxNavigation,
        restart: restartAjaxNavigation,
        getCurrentPage: ajaxNavGetCurrentPage,
        getPrevPage: ajaxNavGetPrevPage,
        isStarted: ajaxNavigationIsStarted

    };

    $.fn.getAjaxNavigation = function( options ) {

        /* Attr on links
* from-cache
*/
        
        /* Attr on assets
* autoload
* reload
*/
    

        // defaults settings
        var defaults = {
            replaceCache:false,
            autoLoadAssets: false,
            autoLoadImages: false,
            autoLoadCss: true,
            autoLoadJs: true,
            changeUriEnabled: true,
            spaceReplacer: '-',
            mode: 'push',
            autoload: false,
            autoStart: true,
            navLinkClass: 'ajax_nav_link',
            transitionIn: function(content) { content.show(); },
            transitionOut: function(content, loadPage) { content.hide(); loadPage(); },
            scrollTop: true,
            baseUrl: window.location.host,
            errorHandler: function(response) { console.log('error'); },
            error404Handler: function(response) { console.log('error 404'); },
            onChange: false,
            beforeDisplay: false
        };

        // set options
        settings = $.extend( true, {}, defaults, options );
        
        // if pushState mode
        if(settings.mode === 'push') {
            ajaxNavigation.data.uriHandler = new PushHandler();
        }
        else if(settings.mode === 'hash') {
            ajaxNavigation.data.uriHandler = new HashHandler();
        }
        else {
            $.error('Navigation mode: ' + settings.mode + ' does not exists, choose hash or push instead');
        }

        
        
        ajaxNavigation.data.content = $(this);

        // auto load images
        if(settings.autoLoadImages) {
            // add img loader container
            if(! $('#img_loader')) {
                $('body').append('<div id="img_loader" style="display:none;"></div>');
            }
        }
        
        // auto load pages
        if(settings.autoload) {
            $('.' + settings.navLinkClass).each(function() {
                uri = getUriFromUrl($(this).attr('href'));
                loadUrl( $(this).attr('href'), uri);
            });
        }
        
        return ajaxNavigation;
    };
    

        // FUNCTIONS AJAX NAVIGATION //////////////////////
        

            function changeUri(uri, naviguate) {
                
                
                if(naviguate === true) {
                    ajaxNavigation.data.prevPage = getCurrentPage();
                    ajaxNavigation.data.uriHandler.change(uri);
                }
                else {
                    ajaxNavigation.data.uriHandler.stop();
                    ajaxNavigation.data.uriHandler.change(uri);
                    
                    timeout = setTimeout(function() {
                        ajaxNavigation.data.uriHandler.start();
                        clearTimeout(timeout);
                    }, 1000);
                }
            }
            
            function changePage(uri) {

                if(ajaxNavigation.data.firstLoad) {
                    ajaxNavigation.data.firstLoad = false;
                }
                
                if(settings.onChange) {
                    link = null;
                    $('.'+ settings.navLinkClass).each(function() {
                        // if link corresponds to requested page
                        if($(this).attr('href').indexOf(getCurrentPage()) !== -1) {
                             link = $(this);
                             return false;
                        }
                    });
                    settings.onChange(uri, link);
                }
                
                // wait for page to be loaded
                $('#'+ajaxNavigation.data.content.attr('id')).bind('loaded', {content: $('#'+ajaxNavigation.data.content.attr('id'))}, function(e) {
                    
                    // autload assets
                    if(settings.autoLoadAssets) {
                        if(settings.autoLoadCss) {
                            // load css
                            $.each(ajaxNavigation.data.css[getCurrentPage()], function(index, value) {
                                
                                if(value.attr('autoload') !== 'false' && (value.attr('reload') !== 'false' || typeof ajaxNavigation.data.cssLoaded[value.attr('href')] === 'undefined')) {
                                    if(! value.attr('media')) {
                                        loadCss(value.attr('href'));
                                    }
                                    else {
                                        loadCss(value.attr('href'), value.attr('media'));
                                    }
                                }
                            });
                        }
                        if(settings.autoLoadJs) {
                            // load js
                            $.each(ajaxNavigation.data.js[getCurrentPage()], function(index, value) {
                                if(value.attr('autoload') !== 'false' && (value.attr('reload') !== 'false' || typeof ajaxNavigation.data.jsLoaded[value.attr('src')] === 'undefined')) {
                                    loadJs(value.attr('src'));
                                }
                            });
                        }
                    }
                    
                    // if current page is loaded
                    if(e.contentIndex === uri) {

                        // replace cache
                        if(settings.replaceCache) {
                            ajaxNavigation.data.contents[ajaxNavigation.data.prevPage] = ajaxNavigation.data.content.html();
                        }

                        settings.transitionOut($('#'+ajaxNavigation.data.content.attr('id')), function() {
                            replaceContent();
                            replaceTitle();
                            
                            if(settings.scrollTop) {
                                $('html, body').animate({
                                    scrollTop:0
                                }, settings.scrollTop);
                            }
                            
                            if(settings.beforeDisplay) {
                                settings.beforeDisplay(uri);
                            }
                            if(ajaxNavigation.data.navigateCallback) {
                                ajaxNavigation.data.navigateCallback();
                                ajaxNavigation.data.navigateCallback = false;
                            }
                            settings.transitionIn($(ajaxNavigation.data.content));
                        });
                        // unbind
                        $(e.data.content).unbind('loaded');
                    }
                });
                
                // build url to load
                url = settings.baseUrl + uri;
                
                // loadPage
                loadUrl(url, uri);
            }
            
            function getCurrentPage() {
                return ajaxNavigation.data.uriHandler.getCurrent();
            }
            
            function getUriFromUrl(url) {
                // format uri
                uri = url.substring(url.indexOf(settings.baseUrl));
                uri = uri.replace(settings.baseUrl, '');

                // remove first slash
                uri = removeFirstSlash(uri);
                
                return uri;
            }

            function loadUrl(url, at) {
                
                
                // add first slash
                if(url.charAt(0) !== '/' && url.indexOf('http://') === -1) {
                    url = '/' + url;
                }
                
                // if loaded from cache and already loaded
                if(ajaxNavigation.data.loadNextFromCache && typeof ajaxNavigation.data.contents[at] !== 'undefined') {
                    
                    // trigger loaded event
                    var event = jQuery.Event("loaded");
                    event.contentIndex = at;
                    $('#'+ajaxNavigation.data.content.attr('id')).trigger(event);
                    
                }
                // if loaded from cache and still loading
                else if(ajaxNavigation.data.loadNextFromCache && typeof ajaxNavigation.data.urlLoading[at] !== 'undefined') {
                    loadingInterval = setInterval(function() {

                        if(typeof ajaxNavigation.data.urlLoading[at] === false) {
                            var event = jQuery.Event("loaded");
                            event.contentIndex = at;
                            $('#'+ajaxNavigation.data.content.attr('id')).trigger(event);
                            clearInterval(loadingInterval);
                        }
                    }, 200);
                }
                else {
                    // loading page begin
                    ajaxNavigation.data.urlLoading[at] = true;
                    
                    // temp container
                    var $div = $("<div/>");

                    $div.load(url, function(response, status, xhr) {
                                                
                        // loading error
                        if(status === "error") {
                            if(xhr.status === 404 && settings.error404Handler) {
                                settings.error404Handler(response);
                            }
                            settings.errorHandler(response, status, xhr);
                            
                        }
                        // no error
                        else {
                            
                            // autoload page assets
                            if(settings.autoLoadAssets) {
                                
                                // autoload css
                                if(settings.autoLoadCss) {
                                    // get css
                                    ajaxNavigation.data.css[at] = [];
                                    $div.find('link[rel="stylesheet"]').each(function() {
                                        if($(this).attr('href')){
                                            ajaxNavigation.data.css[at].push($(this));
                                        }
                                    });
                                }
                                // autolaod js
                                if(settings.autoLoadCss) {
                                    // get js
                                    ajaxNavigation.data.js[at] = [];
                                    $div.find('script').each(function() {
                                        if($(this).attr('src')) {
                                            ajaxNavigation.data.js[at].push($(this));
                                        }
                                    });
                                }
                            }
                            
                            // auto load images
                            if(settings.autoLoadImages) {
                                // browse image on page
                                $div.find('img').each(function() {
                                    // if image not loaded yet
                                    if( ! $('#img_loader').children('img[src="'+ $(this).attr('src') +'"]')) {
                                        // load image
                                        $('#img_loader').append('<img src="'+ $(this).attr('src') +'">');
                                    }
                                });
                            }

                            // store page content
                            ajaxNavigation.data.contents[at] = $div.find('#'+$(ajaxNavigation.data.content).attr('id')).html();
                            // store page title
                            ajaxNavigation.data.titles[at] = $div.find('title').html();
                        }

                        // loading page end
                        ajaxNavigation.data.urlLoading[at] = false;

                        // trigger loaded event
                        var event = jQuery.Event("loaded");
                        event.contentIndex = at;
                        $('#'+ajaxNavigation.data.content.attr('id')).trigger(event);
                    });
                }
            }
            
            function replaceContent() {
                $(ajaxNavigation.data.content).html( ajaxNavigation.data.contents[getCurrentPage()] );
            }
            function replaceTitle() {
                // change title
                if(typeof ajaxNavigation.data.titles[getCurrentPage()] !== 'undefined') {
                    document.title = ajaxNavigation.data.titles[getCurrentPage()];
                }
            }
            
            function loadCss(css, media) {
                if(typeof media === 'undefined') {
                    media = 'screen';
                }
                var fileref=document.createElement("link");
                fileref.setAttribute("rel", "stylesheet");
                fileref.setAttribute("media", media);
                fileref.setAttribute("type", "text/css");
                fileref.setAttribute("href", css);
                document.getElementsByTagName("head")[0].appendChild(fileref);
                ajaxNavigation.data.cssLoaded[css] = true;
            }
            
            function loadJs(js) {
                $.getScript(js);
                ajaxNavigation.data.jsLoaded[js] = true;
            }
            
            function removeFirstSlash(str) {
                if(str.charAt(0) === '/') {
                    str = str.substring(1);
                }
                return str;
            }
            
            function onNavClickHandler(e) {

                if(typeof $(this).attr('from-cache') !== 'undefined' && $(this).attr('from-cache') === 'false') {
                    fromCache = false;
                }
                else {
                    fromCache = true;
                }

                if(ajaxNavigation.data.started) {
                    // navigate to page
                    ajaxNavigation.navigateTo( getUriFromUrl($(this).attr('href')), fromCache );
                    // prevent default behavior
                    e.preventDefault();
                    return false;
                }
                return true;
            }

        // END FUNCTIONS AJAX NAVIGATION //////////////////

        // API AJAX NAVIGATION //////////////////////
        
            function initNavigation(ajaxNavigation) {
                
                if(!ajaxNavigation.data.init) {
                    // auto start navigation handle
                    if(settings.autoStart && ! ajaxNavigation.data.started) {
                        ajaxNavigation.start();
                    }

                    // autoload first page
                    if(settings.mode === 'hash') {
                        if(getCurrentPage() !== '' && ajaxNavigation.data.firstLoad) {
                            $(ajaxNavigation.data.content).hide();
                            changePage(getCurrentPage());
                        }

                    }
                    
                    else if(settings.mode === 'push') {
                        if(settings.onChange) {
                            link = null;
                            $('.'+ settings.navLinkClass).each(function() {
                                // if link corresponds to requested page
                                if($(this).attr('href').indexOf(getCurrentPage()) !== -1) {
                                     link = $(this);
                                     return false;
                                }
                            });
                            settings.onChange(getCurrentPage(), link);
                        }
                        if(settings.beforeDisplay) {
                            settings.beforeDisplay(getCurrentPage());
                        }
                    }
                }
            }

            function ajaxNavigationIsStarted() {
                return ajaxNavigation.data.started;
            }

            function ajaxNavGetCurrentPage() {
                return getCurrentPage();
            }

            function ajaxNavGetPrevPage() {
                return ajaxNavigation.data.prevPage;
            }

            function navigateTo(uri, fromCache, callback) {
                
                if(typeof fromCache === 'undefined') {
                    fromCache = true;
                }
                
                if(typeof callback !== 'undefined') {
                    ajaxNavigation.data.navigateCallback = callback;
                }
                
                ajaxNavigation.data.loadNextFromCache = fromCache;
                
                // if navigation started and new page different from current or first load
                if((ajaxNavigation.data.started && getCurrentPage() !== uri) || ajaxNavigation.data.firstLoad) {
                    changeUri(uri, true);
                }
            }
            
            function restartAjaxNavigation() {

                ajaxNavigation.stop();
                initNavigation(ajaxNavigation);
            }

            function startAjaxNavigation() {
                
                // if navigation is not started
                if(!ajaxNavigation.data.started) {
                    
                    ajaxNavigation.data.started = true;
                    
                    if(!ajaxNavigation.data.init) {
                        ajaxNavigation.init(ajaxNavigation);
                    }
                    
                    ajaxNavigation.data.uriHandler.start();
                }
            }

            function stopAjaxNavigation() {
                // if navigation started
                if(ajaxNavigation.data.started) {
                    
                    ajaxNavigation.data.init = false;
                    ajaxNavigation.data.started = false;

                    ajaxNavigation.data.uriHandler.stop();
                }
            }

        // END API AJAX NAVIGATION //////////////////
        
        
        // URI HANDLERS ////////////////////////////
        
            // PUSH HANDLER ////////////////////////
            
                function PushHandler() {
                    this.name = "push";
                    if( typeof PushHandler.initialized === "undefined" ) {
                        
                        ////////////////////////////////////////////////////////////////////////////////// PUSH : CHANGE
                        
                        PushHandler.prototype.change = function(uri) {
                                   
                                                       
                            state = uri;
                            if(state === '') {
                                state = '/' + settings.baseUrl;
                            }

                            state = state.replace('//', '/');
                             
                            window.history.pushState("", uri, state);
                            changePage(uri);
                        };
                        
                        ////////////////////////////////////////////////////////////////////////////////// PUSH : GET_CURRENT
                        
                        PushHandler.prototype.getCurrent = function() {
                            
                            if(settings.baseUrl.indexOf('http://') !== -1) {
                                href = window.location.href.replace(settings.baseUrl, '');
                            }
                            else {
                                href = window.location.pathname.replace(settings.baseUrl, '');
                            }
                            
                            
                            return removeFirstSlash( href.replace('//', '/') );
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
                        
                        PushHandler.initialized = true;
                        
                        ///////////////////////////////////////////////////////////////////////////////// PUSH : FUNCTIONS
                        
                        PushHandler.prototype.onPopState = function() {

                            if(!ajaxNavigation.data.firstLoad) {
                                changePage(ajaxNavigation.data.uriHandler.getCurrent());
                            }
                           
                        };
                    }
                }
            
            // END PUSH HANDLER ///////////////////
            
            // HASH HANDLER ////////////////////////
            
                function HashHandler() {
                    this.name = "hash";
                    if( typeof PushHandler.initialized === "undefined" ) {
                        
                        
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
                                window.onhashchange = function () {
                                    changePage( ajaxNavigation.data.uriHandler.getCurrent() );
                                };
                            }
                            // event not supported
                            else {
                                ajaxNavigation.data.storedHash = window.location.hash;
                                ajaxNavigation.data.interval = window.setInterval(function () {
                                    if (window.location.hash !== ajaxNavigation.data.storedHash) {
                                        ajaxNavigation.data.storedHash = window.location.hash;
                                        changePage( ajaxNavigation.data.uriHandler.getCurrent() );
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
                        
                        HashHandler.initialized = true;
                    }
                }
            
            // END HASH HANDLER ///////////////////
        
        // END URI HANDLERS ////////////////////////
        
}) (jQuery);
    
// END AJAX NAVIGATION ////////////////////////////////////