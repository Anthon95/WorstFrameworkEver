{extends file="WFE/page.tpl"}

{block name="content"}
    <section>
        <p>I do something !</p>
        <p>Here is dynamic content : <img src="{link route="WFEPublicImg" params=['img' => 'test.jpg']}"></p>
        <p>Parameter : {$parameter}</p>
    </section>
{/block}