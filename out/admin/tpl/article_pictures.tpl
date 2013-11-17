[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function DeletePic( sField )
{
    var oForm = document.getElementById("myedit");
    document.getElementById(sField).value="";
    oForm.fnc.value='save';
    oForm.submit();
}
function editThis( sID )
{
    var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
    oTransfer.oxid.value = sID;
    oTransfer.cl.value = top.basefrm.list.sDefClass;

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById( "search" );
    oSearch.oxid.value = sID;
    oSearch.actedit.value = 0;
    oSearch.submit();
}
//-->
</script>

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]


<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="article_pictures">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<form name="myedit" id="myedit" enctype="multipart/form-data" action="[{ $shop->selflink }]" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="[{$iMaxUploadFileSize}]">
[{ $shop->hiddensid }]
<input type="hidden" name="cl" value="article_pictures">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[article__oxid]" value="[{ $oxid }]">
<input type="hidden" name="voxid" value="[{ $oxid }]">
<input type="hidden" name="oxparentid" value="[{ $oxparentid }]">


<table cellspacing="0" cellpadding="0" border="0" width="98%">
<colgroup><col width="40%" span="1"><col width="20%"></colgroup>
<tr>
    <td valign="top" class="edittext">
        <table cellspacing="0" cellpadding="0" border="0">

        [{ if $oxparentid }]
        <tr>
            <td class="edittext" width="120">
            <b>[{ oxmultilang ident="GENERAL_VARIANTE" }]</b>
            </td>
            <td class="edittext" colspan="2">
            <a href="Javascript:editThis('[{ $parentarticle->oxarticles__oxid->value}]');" class="edittext"><b>[{ $parentarticle->oxarticles__oxartnum->value }] [{ $parentarticle->oxarticles__oxtitle->value }]</b></a>
            </td>
        </tr>
        [{ /if}]

        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_ICON" }]
            </td>
            <td class="edittext">
            <input id="oxicon" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxicon->fldmax_length}]" name="editval[oxarticles__oxicon]" value="[{$edit->oxarticles__oxicon->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxicon->value=="nopic.jpg" || $edit->oxarticles__oxicon->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxicon');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_ICONUPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[ICO@oxarticles__oxicon]" type="file" [{ $readonly }]>
            </td>
        </tr>

        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_THUMB" }]
            </td>
            <td class="edittext">
            <input id="oxthumb" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxthumb->fldmax_length}]" name="editval[oxarticles__oxthumb]" value="[{$edit->oxarticles__oxthumb->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxthumb->value=="nopic.jpg" || $edit->oxarticles__oxthumb->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxthumb');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_THUMBUPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[TH@oxarticles__oxthumb]" type="file" [{ $readonly }]>
            </td>
        </tr>

        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC1" }]
            </td>
            <td class="edittext">
            <input id="oxpic1" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxpic1->fldmax_length}]" name="editval[oxarticles__oxpic1]" value="[{$edit->oxarticles__oxpic1->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxpic1->value=="nopic.jpg" || $edit->oxarticles__oxpic1->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxpic1');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC1UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[P1@oxarticles__oxpic1]" type="file" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC2" }]
            </td>
            <td class="edittext">
            <input id="oxpic2" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxpic2->fldmax_length}]" name="editval[oxarticles__oxpic2]" value="[{$edit->oxarticles__oxpic2->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxpic2->value=="nopic.jpg" || $edit->oxarticles__oxpic2->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxpic2');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC2UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[P2@oxarticles__oxpic2]" type="file" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC3" }]
            </td>
            <td class="edittext">
            <input id="oxpic3" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxpic3->fldmax_length}]" name="editval[oxarticles__oxpic3]" value="[{$edit->oxarticles__oxpic3->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxpic3->value=="nopic.jpg" || $edit->oxarticles__oxpic3->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxpic3');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC3UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[P3@oxarticles__oxpic3]" type="file" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC4" }]
            </td>
            <td class="edittext">
            <input id="oxpic4" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxpic4->fldmax_length}]" name="editval[oxarticles__oxpic4]" value="[{$edit->oxarticles__oxpic4->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxpic4->value=="nopic.jpg" || $edit->oxarticles__oxpic4->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxpic4');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC4UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[P4@oxarticles__oxpic4]" type="file" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC5" }]
            </td>
            <td class="edittext">
            <input id="oxpic5" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxpic5->fldmax_length}]" name="editval[oxarticles__oxpic5]" value="[{$edit->oxarticles__oxpic5->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxpic5->value=="nopic.jpg" || $edit->oxarticles__oxpic5->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxpic5');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC5UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[P5@oxarticles__oxpic5]" type="file" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="ARTICLE_PICTURES_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{ $readonly }]><br>
            </td>
        </tr>
        </table>
    </td>
    <!-- Anfang rechte Seite -->
    <td valign="top" class="edittext" align="left">

        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC6" }]
            </td>
            <td class="edittext">
            <input id="oxpic6" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxpic6->fldmax_length}]" name="editval[oxarticles__oxpic6]" value="[{$edit->oxarticles__oxpic6->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxpic6->value=="nopic.jpg" || $edit->oxarticles__oxpic6->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxpic6');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC6UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[P6@oxarticles__oxpic6]" type="file" [{ $readonly }]>
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC7" }]
            </td>
            <td class="edittext">
            <input id="oxpic7" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxpic7->fldmax_length}]" name="editval[oxarticles__oxpic7]" value="[{$edit->oxarticles__oxpic7->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxpic7->value=="nopic.jpg" || $edit->oxarticles__oxpic7->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxpic7');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_PIC7UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[P7@oxarticles__oxpic7]" type="file" [{ $readonly }]>
            </td>
        </tr>

        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_ZOOM1" }]
            </td>
            <td class="edittext">
            <input id="oxzoom1" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxzoom1->fldmax_length}]" name="editval[oxarticles__oxzoom1]" value="[{$edit->oxarticles__oxzoom1->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxzoom1->value=="nopic.jpg" || $edit->oxarticles__oxzoom1->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxzoom1');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_ZOOM1UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[Z1@oxarticles__oxzoom1]" type="file" [{ $readonly }]>
            </td>
        </tr>

        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_ZOOM2" }]
            </td>
            <td class="edittext">
            <input id="oxzoom2" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxzoom2->fldmax_length}]" name="editval[oxarticles__oxzoom2]" value="[{$edit->oxarticles__oxzoom2->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxzoom2->value=="nopic.jpg" || $edit->oxarticles__oxzoom2->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxzoom2');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_ZOOM2UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[Z2@oxarticles__oxzoom2]" type="file" [{ $readonly }]>
            </td>
        </tr>

        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_ZOOM3" }]
            </td>
            <td class="edittext">
            <input id="oxzoom3" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxzoom3->fldmax_length}]" name="editval[oxarticles__oxzoom3]" value="[{$edit->oxarticles__oxzoom3->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxzoom3->value=="nopic.jpg" || $edit->oxarticles__oxzoom3->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxzoom3');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_ZOOM3UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[Z3@oxarticles__oxzoom3]" type="file" [{ $readonly }]>
            </td>
        </tr>

        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_ZOOM4" }]
            </td>
            <td class="edittext">
            <input id="oxzoom4" type="text" class="editinput" size="30" maxlength="[{$edit->oxarticles__oxzoom4->fldmax_length}]" name="editval[oxarticles__oxzoom4]" value="[{$edit->oxarticles__oxzoom4->value}]" [{ $readonly }]>
            </td>
            <td class="edittext">
            [{ if (!($edit->oxarticles__oxzoom4->value=="nopic.jpg" || $edit->oxarticles__oxzoom4->value=="nopic_ico.jpg")) }]
            <a href="Javascript:DeletePic('oxzoom4');" class="delete left" [{include file="help.tpl" helpid=item_delete}]></a>
            [{/if}]
            </td>
        </tr>
        <tr>
            <td class="edittext">
            [{ oxmultilang ident="ARTICLE_PICTURES_ZOOM4UPLOAD" }]
            </td>
            <td class="edittext" colspan="2">
            <input class="editinput" name="myfile[Z4@oxarticles__oxzoom4]" type="file" [{ $readonly }]>
            </td>

        </tr>

        </table>

    </td>

    [{if $edit->oxarticles__oxthumb->value }]
    <td valign="top" align="center">
            [{ oxmultilang ident="GENERAL_THUMB" }]
            <br>
            <img src="[{$edit->dimagedir}]/0/[{$edit->oxarticles__oxthumb->value }]" border="0" hspace="0" vspace="0">
    </td>
    [{/if}]
    </tr>

</table>

</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
