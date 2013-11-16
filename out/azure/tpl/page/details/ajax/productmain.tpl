[{assign var="oDetailsProduct" value=$oView->getProduct()}]
[{assign var="currency" value=$oView->getActCurrency()}]
[{assign var="oPictureProduct" value=$oView->getPicturesProduct()}]

[{oxscript add="$(function(){oxid.initDetailsPagePartial();});"}]

[{include file="page/details/inc/productmain.tpl"}]

[{oxscript}]
