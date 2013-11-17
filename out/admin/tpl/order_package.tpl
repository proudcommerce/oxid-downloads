[{include file="headitem.tpl" title="ORDER_PACKAGE_TITLE"|oxmultilangassign box=" "}]
<script type="text/javascript">
<!--
function printWindow()
{
	bV = parseInt(navigator.appVersion);
	if (bV >= 4)
		window.print();
}
//  End -->
</script>
<style media="print"> #noprint{ display:none; }</style>
<br>
<div id="noprint"><br>
<a class="listitem" href="javascript:printWindow();"><b>[{ oxmultilang ident="ORDER_PACKAGE_SHOWPACKLIST" }]</b></a><br>
<br><br></div>

<span class="listitem">
<b>[{ oxmultilang ident="ORDER_PACKAGE_PACKLIST" }]</b><br><br>
</span>

[{foreach from=$resultset item=order}]

<table cellspacing="0" cellpadding="0" border="0" width="98%" style="padding-top : 10px; padding-bottom : 10px; padding-left : 5px;  padding-right : 5px; border : 1px #000000; border-style : solid solid solid solid;">
<tr>
	<td class="listitem" width="150" valign="top">
		<b>[{ oxmultilang ident="GENERAL_BILLADDRESS" }]<br></b>
                [{assign var=_sal value=$order->fields.OXBILLSAL}]
                [{oxmultilang ident="GENERAL_SALUTATION_$_sal" noerror="yes" alternative=$_sal }]&nbsp;[{ $order->fields.OXBILLFNAME }]&nbsp;[{ $order->fields.OXBILLLNAME }]<br>
		[{ $order->fields.OXBILLCOMPANY }]<br>
		[{ $order->fields.OXBILLSTREET }]  [{ $order->fields.OXBILLSTREETNR }]<br>
		[{ $order->fields.OXBILLZIP }]&nbsp;[{ $order->fields.OXBILLCITY }]<br>
		[{ $order->fields.OXBILLCOUNTRY }]<br>
        [{ oxmultilang ident="GENERAL_USTID" }]: [{ $order->fields.OXBILLUSTID}]<br>
		[{ $order->fields.OXBILLADDINFO }]<br>
		[{ oxmultilang ident="ORDER_PACKAGE_FON" }]: [{ $order->fields.OXBILLFON }]<br>
		[{ oxmultilang ident="ORDER_PACKAGE_FAX" }]: [{ $order->fields.OXBILLFAX }]<br>
		[{ if $order->fields.OXREMARK }]<b><br>[{ oxmultilang ident="ORDER_PACKAGE_REMARK" }]: <br>[{ $order->fields.OXREMARK }]</b><br>[{/if}]
	</td>
	<td class="listitem" width="150" valign="top">
		<b>[{ oxmultilang ident="GENERAL_DELIVERYADDRESS" }]:<br></b>
                [{assign var=_sal value=$order->fields.OXBILLSAL}]
                [{oxmultilang ident="GENERAL_SALUTATION_$_sal" noerror="yes" alternative=$_sal }]&nbsp;[{if $order->fields.OXDELFNAME}][{ $order->fields.OXDELFNAME }][{ else }][{ $order->fields.OXBILLFNAME }][{/if}]&nbsp;[{if $order->fields.OXDELLNAME }][{$order->fields.OXDELLNAME }][{else}][{$order->fields.OXBILLLNAME }][{/if}]<br>
		[{ if $order->fields.OXDELCOMPANY }][{ $order->fields.OXDELCOMPANY }][{else}][{ $order->fields.OXBILLCOMPANY }][{/if}]<br>
		[{ if $order->fields.OXDELSTREET }][{ $order->fields.OXDELSTREET }]&nbsp;[{ $order->fields.OXDELSTREETNR }][{else}][{ $order->fields.OXBILLSTREET }]&nbsp;[{ $order->fields.OXBILLSTREETNR }][{/if}]<br>
		[{ if $order->fields.OXDELZIP }][{ $order->fields.OXDELZIP }][{else}][{ $order->fields.OXBILLZIP }][{/if}]&nbsp;[{ if $order->fields.OXDELCITY }][{ $order->fields.OXDELCITY }][{else}][{ $order->fields.OXBILLCITY }][{/if}]<br>
		[{ if $order->fields.OXDELCOUNTRY }][{ $order->fields.OXDELCOUNTRY }][{else}][{ $order->fields.OXBILLCOUNTRY }][{/if}]<br>
		[{ if $order->fields.OXDELADDINFO }][{ $order->fields.OXDELADDINFO }][{else}][{ $order->fields.OXBILLADDINFO }][{/if}]<br><br>
		[{ oxmultilang ident="ORDER_PACKAGE_FON" }]: [{ if $order->fields.OXDELFON }][{ $order->fields.OXDELFON }][{else}][{ $order->fields.OXBILLFON }][{/if}]<br>
		[{ oxmultilang ident="ORDER_PACKAGE_FAX" }]: [{ if $order->fields.OXDELFAX }][{ $order->fields.OXDELFAX }][{else}][{ $order->fields.OXBILLFAX }][{/if}]<br>
	</td>
	<td class="packitem" valign="top">
	 [{ oxmultilang ident="ORDER_PACKAGE_ORDERNR1" }][{ $order->fields.OXORDERNR}] - [{ oxmultilang ident="ORDER_PACKAGE_ORDERNR2" }] [{ $order->fields.OXORDERDATE|oxformdate:"datetime":true }]<br><br>
		<table cellspacing="2" cellpadding="0" border="0" width="100%">
	[{foreach from=$order->articles item=article}]
		<tr>
			<td class="packitem" valign="top"><b>[{ $article.OXAMOUNT }]</b></td>
			<td class="packitem" valign="top">[{ $article.OXARTNUM }] </td>
			<td class="packitem" valign="top">[{ $article.OXTITLE }] </td>
			<td class="packitem" valign="top">[{ $article.OXSELVARIANT}]</td>
			<td class="packitem" valign="middle"><img src="[{$shop->imagedir}]/rectangle.gif" alt="" width="20" height="20" border="0"></td>
		</tr>
		[{if $article.sPostCardName}]
		<tr>
			<td class="listitem" valign="top"></td>
			<td class="listitem" valign="top"></td>
			<td class="listitem" valign="top">[{ $article.sPostCardName }]</td>
			<td class="listitem" valign="top"></td>
			<td class="listitem" valign="middle"></td>
		</tr>
		[{/if}]
	[{/foreach}]
		[{if $order->sPostCardName}]
		<tr>
			<td class="listitem" valign="top"></td>
			<td class="listitem" valign="top"></td>
			<td class="listitem" valign="top"><b>[{ $order->sPostCardName }]</b></td>
			<td class="listitem" valign="top"></td>
			<td class="listitem" valign="middle"></td>
		</tr>
		[{/if}]
		</table>
	</td>
</tr>
</table>
<br>
[{/foreach}]

<script type="text/javascript">
if (parent.parent) 
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="ORDER_PACKAGE_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="ORDER_PACKAGE_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>
