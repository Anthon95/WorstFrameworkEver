<!DOCTYPE html>
<html>

    <head>
        <title>{block name="page_title"}Default Page Title{/block}</title>
        <meta name="description" content="{block name=page_description}Default Page Description{/block}">
        <meta name="keyword" content="{block name=page_keywords}default,key,words{/block}">
    </head>

    <body>
        
        {include file="WFE/beforeContent.tpl"}

        <div id="page-content">
            {$content}
        </div>
        
        {include file="WFE/afterContent.tpl"}

    <script type="text/javascript" src="{link route="WFEPublicJs" params=[js => "core/libs/jQuery/jquery.min.js"]}"></script>
    <script type="text/javascript" src="{link route="WFEPublicJs" params=[js => "core/libs/jQuery/plugins/jquery.form.min.js"]}"></script>
    <script type="text/javascript" src="{link route="WFEPublicJs" params=[js => "core/core.js"]}"></script>

    <script type="text/javascript" id="initScript">
        
        {include file="WFE/js/config.tpl.js"}
        WFEConfig.appRoot = "{$appRoute}";
        wfe = new WFE_API(WFEConfig);   
       
        {literal}
        wfe.navigate('do', {'parameter':'ok'});
        {/literal}
        
    </script>
    
    <script type="text/javascript" src="{link route="WFEPublicJs" params=[js => "app.js"]}"></script>

</body>

</html>