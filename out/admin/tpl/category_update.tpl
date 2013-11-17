[{include file="headitem.tpl" title="CATEGORY_UPDATE_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
[{ if $updatelist == 1}]
    UpdateList('[{ $oxid }]');
[{ /if}]

function UpdateList( sID)
{
    var oSearch = document.getElementById("search");
    oSearch.oxid.value=sID;
    oSearch.submit();
}

function refreshParent() 
{
    var oSearch = opener.parent.list.document.getElementById("search");
    oSearch.oxid.value='-1'; 
    oSearch.submit();
}

refreshParent();

//-->
</script>

<br>
&nbsp;&nbsp;&nbsp;<button onclick="window.close()">[{ oxmultilang ident="CATEGORY_UPDATE_CLOSE" }]</button>
<br><br>


[{include file="bottomitem.tpl"}]
