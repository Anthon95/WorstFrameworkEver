<!DOCTYPE html>
<html>

    <head>
        <title>{block name=title}Default Page Title{/block}</title>
        <meta name="description" content="{block name=description}Default Page Description{/block}">
        <meta name="keyword" content="{block name=keywords}default,key,words{/block}">
    </head>

    <body>

        <div id="page-content">
            
        </div>

    <script type="text/javascript" src="{link route="WFEPublicJs" params=[js => "core/libs/jQuery/jquery.min.js"]}"></script>

    <script>alert('je dois charger : {$pageToLoad}');</script>


</body>

</html>