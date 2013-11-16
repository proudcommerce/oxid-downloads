[{* Important ! render page head and body to collect scripts and styles *}]
[{capture append="oxidBlock_pageHead"}]
    <title>[{$oView->getTitle()}]</title>
    [{foreach from=$oxidBlock_head item="_block"}]
        [{$_block}]
    [{/foreach}]
[{/capture}]
<!DOCTYPE HTML>
<html lang="[{ $oView->getActiveLangAbbr() }]">
<head>
    [{foreach from=$oxidBlock_pageHead item="_block"}]
        [{$_block}]
    [{/foreach}]
    [{oxstyle}]
</head>
<body>
    [{foreach from=$oxidBlock_pageBody item="_block"}]
        [{$_block}]
    [{/foreach}]

</body>
</html>