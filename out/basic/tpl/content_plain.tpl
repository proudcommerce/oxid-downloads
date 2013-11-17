[{assign var="oContent" value=$oView->getContent()}]
[{assign var="template_title" value=$oContent->oxcontents__oxtitle->value}]
[{include file="_header_plain.tpl" title=$template_title location=$template_title}]

    <h1 class="boxhead">[{$template_title}]</h1>
    <div class="box">[{ oxcontent oxid=$oView->getContentId() }]</div>

[{insert name="oxid_tracker" title=$template_title }]
[{include file="_footer_plain.tpl"}]