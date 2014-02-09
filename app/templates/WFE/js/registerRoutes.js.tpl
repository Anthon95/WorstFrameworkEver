{foreach $WFERoutes as $route}

WFE.router.registerRoute({$route.getName}, {$route.getPath});

{/foreach}