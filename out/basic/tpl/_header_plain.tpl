<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html[{if $oView->getActiveLangAbbr()}] lang="[{ $oView->getActiveLangAbbr() }]"[{/if}]>
<head>
    [{assign var="_titlesuffix" value=$_titlesuffix|default:$oView->getTitleSuffix()}]
    [{assign var="title" value=$title|default:$oView->getTitle() }]
    <title>[{ $oView->getTitlePrefix() }][{if $title}] | [{$title|strip_tags}][{/if}][{if $_titlesuffix}] | [{$_titlesuffix}][{/if}]</title>
    <meta http-equiv="Content-Type" content="text/html; charset=[{$charset}]">
    [{if $oView->noIndex()}]
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
    [{elseif $oView->noIndex() == 2 }]
    <meta name="ROBOTS" content="NOINDEX, FOLLOW">
    [{/if}]
    [{if $oView->getMetaDescription()}]<meta name="description" content="[{$oView->getMetaDescription()}]">[{/if}]
    [{if $oView->getMetaKeywords()}]<meta name="keywords" content="[{$oView->getMetaKeywords()}]">[{/if}]
    <link rel="shortcut icon" href="[{ $oViewConf->getBaseDir()}]favicon.ico">
    <link rel="stylesheet" type="text/css" href="[{ $oViewConf->getResourceUrl() }]oxid.css">
    <!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="[{ $oViewConf->getResourceUrl() }]oxidbc.css"><![endif]-->

    [{if $rsslinks}]
      [{foreach from=$rsslinks item='rssentry'}]
        <link rel="alternate" type="application/rss+xml" title="[{$rssentry.title|strip_tags}]" href="[{$rssentry.link}]">
      [{/foreach}]
    [{/if}]
</head>
<body>
   <div id="body" class="plain">

