[{capture append="oxidBlock_content"}]
    [{assign var="oContent" value=$oView->getContent()}]
    [{assign var="tpl" value=$oViewConf->getActTplName()}]
    <h1 class="pageHead">[{$oContent->oxcontents__oxtitle->value}]</h1>
    [{ oxcontent oxid=$oView->getContentId() }]
[{/capture}]
[{include file="layout/page.tpl" sidebar="Left"}]
