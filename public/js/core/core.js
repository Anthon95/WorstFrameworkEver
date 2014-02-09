

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// ROUTER CLASS 
// 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function Router() {

    var routes = {};

    if (typeof Router.initialized === "undefined") {

        Router.initialized = true;
        
        ////////////////////////////////////////////////////////////////////////////////// ROUTER : registerRoute

        Router.prototype.registerRoute = function(routeName, path) {

            if (typeof routeName !== 'undefined' && typeof path !== 'undefined') {

                routes[routeName] = path;
            }
        };

        ////////////////////////////////////////////////////////////////////////////////// ROUTER : getLink

        Router.prototype.getLink = function(routeName, params) {

            if (typeof routes[routeName] !== 'undefined') {

                var route = routes[routeName];

                return injectParams(route.path, params);
            }
            else {
                return null;
            }
        };

        ////////////////////////////////////////////////////////////////////////////////// PRIVATE

        function injectParams(path, params) {
            var path_segs = path.split('/');
            for (index in path_segs) {
                console.log(path_segs[index]);
            }
        }

    }
}
