<!DOCTYPE html>
<html>

    <head>
        <title>{block name=page_title}Default Page Title{/block}</title>
        <meta name="description" content="{block name=page_description}Default Page Description{/block}">
        <meta name="keyword" content="{block name=page_keywords}default,key,words{/block}">
    </head>

    <body>

        <div id="page-content">

        </div>

    <script type="text/javascript" src="{link route="WFEPublicJs" params=[js => "core/libs/jQuery/jquery.min.js"]}"></script>
    <script type="text/javascript" src="{link route="WFEPublicJs" params=[js => "core/libs/jQuery/plugins/jquery.form.min.js"]}"></script>
    <script type="text/javascript" src="{link route="WFEPublicJs" params=[js => "core/core.js"]}"></script>

    <script type="text/javascript" id="initScript">
        
        {include file="WFE/js/config.tpl.js"}
        
        WFEConfig.appRoute = "{$appRoute}";
        
        wfe = new WFE("{$routeToLoad}", WFEConfig);
        
        
    </script>


</body>

</html>