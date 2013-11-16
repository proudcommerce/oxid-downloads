[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]


[{ if $error}]<div class="errorbox">[{ $error }]</div>[{/if}]
[{ if $message}]<div class="messagebox">[{ $message }]</div>[{/if}]

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="shop_license">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>





        <table border="0" width="98%">
          <tr>
            <td class="edittext">
            <br><strong>[{ oxmultilang ident="SHOP_LICENSE_VERSION" }]</strong>
            </td>
            <td class="edittext">
            <b>[{ oxmultilang ident="GENERAL_OXIDESHOP" }]
                [{$oView->getShopEdition()}] [{$oView->getShopVersion()}]_[{$oView->getRevision()}]
                [{if $oView->isDemoVersion()}]
                    [{ oxmultilang ident="SHOP_LICENSE_DEMO" }]
                [{/if}]
            </b>
            </td>
            <td class="edittext">
                <form action="http://admin.oxid-esales.com/CE/onlinecheck.php" method=post target=_blank>
                <input type="hidden" name="myversion"  value="[{$edit->oxshops__oxversion->value }]">
                <input type="submit" class="edittext" name="save" value="&nbsp;&nbsp;&nbsp;&nbsp;[{ oxmultilang ident="SHOP_LICENSE_ONLINECHECK" }]&nbsp;&nbsp;&nbsp;&nbsp;" [{ $readonly }]>
              </form>
            </td>
          </tr>



        </table>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
