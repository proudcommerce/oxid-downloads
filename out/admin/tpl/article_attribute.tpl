[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function EditThis( sID)
{
    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value='article_main';
    oTransfer.submit();

    var oSearch = parent.list.document.getElementById("search");
    oSearch.actedit.value = 0;
    oSearch.oxid.value=sID;
    oSearch.submit();
}

function VerifySelectedAttribute ()
{
    var countSelected = 0;
    var allartattr = document.getElementsByName("allartattr[]");
    var iCtr = 0;
    if ( allartattr != null)
    {   oOptions = allartattr.item(0);
        while ( oOptions.item(iCtr) != null)
        {   if (oOptions.item(iCtr).selected == true)
                countSelected++;
            iCtr++;
        }
    }

    if (countSelected > 1) { alert("[{ oxmultilang ident="ARTICLE_ATTRIBUTE_TOOMANYATTRIBUTES" }]"); return false; }
    if (countSelected == 0) { alert("[{ oxmultilang ident="ARTICLE_ATTRIBUTE_NOATTRIBUTES" }]"); return false; }

    document.myedit.fnc.value = 'changeAttributeValue';
    document.myedit.submit();
    return true;
}

function VerifyAttributeValue ()
{   // commented due to #957
    //if ("" != document.myedit.attr_value.value)
    //{
        document.myedit.fnc.value = 'saveAttributeValue';
        document.myedit.submit();
        return true;
    //} else alert("Please enter a new value.");
}


function SetSticker( sStickerId, oObject)
{
    if ( oObject.selectedIndex != -1)
    {   oSticker = document.getElementById(sStickerId);
        oSticker.style.display = "";
        oSticker.style.backgroundColor = "#FFFFCC";
        oSticker.style.borderWidth = "1px";
        oSticker.style.borderColor = "#000000";
        oSticker.style.borderStyle = "solid";
        oSticker.innerHTML         = oObject.item(oObject.selectedIndex).innerHTML;
    }
    else
        oSticker.style.display = "none";
}
//-->
</script>

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="article_attribute">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

  <table cellspacing="0" cellpadding="0" border="0" width="96%">

    <tr>
      <td valign="top" class="edittext">

        [{if $oxparentid }]
          <b>[{ oxmultilang ident="GENERAL_VARIANTE" }]<a href="Javascript:EditThis('[{ $parentarticle->oxarticles__oxid->value}]');" class="edittext"><b>[{ $parentarticle->oxarticles__oxartnum->value }] [{ $parentarticle->oxarticles__oxtitle->value }]</b></a><br>
          <br>
        [{/if}]

          [{oxhasrights object=$edit readonly=$readonly }]
          <input type="button" value="[{ oxmultilang ident="ARTICLE_ATTRIBUTE_ASSIGNATTRIBUTE" }]" class="edittext" onclick="JavaScript:showDialog('?cl=article_attribute&aoc=1&oxid=[{ $oxid }]');">
          [{/oxhasrights}]

          [{ if !$edit->blForeignArticle }]
          <br><br>
          <a class="edittext" href="[{ $shop->selflink }]?cl=attribute" target="_new"><b>[{ oxmultilang ident="ARTICLE_ATTRIBUTE_OPENINNEWWINDOW" }]</b></a>
          [{/if}]

      </td>

      <!-- Anfang rechte Seite -->
      <td valign="top" class="edittext" align="left" width="50%">
        [{oxhasrights object=$edit readonly=$readonly }]
          <input type="button" value="[{ oxmultilang ident="ARTICLE_ATTRIBUTE_ASSIGNSELECTLIST" }]" class="edittext" onclick="JavaScript:showDialog('?cl=article_attribute&aoc=2&oxid=[{ $oxid }]');">
        [{/oxhasrights}]
      </td>
      <!-- Ende rechte Seite -->
    </tr>
  </table>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
