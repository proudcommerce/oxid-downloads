<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>[{ oxmultilang ident="GUI_TITLE" }]</title>
    <meta http-equiv="Content-Type" content="text/html; charset=[{$charset}]">

    <link rel="stylesheet" type="text/css" href="[{$shop->basetpldir}]yui/build/reset-fonts/reset-fonts.css">
    <link rel="stylesheet" type="text/css" href="[{$shop->basetpldir}]yui/build/base/base-min.css">
    <link rel="stylesheet" type="text/css" href="[{$shop->basetpldir}]yui/build/assets/skins/sam/skin.css">

    <script type="text/javascript" src="[{$shop->basetpldir}]yui/build/utilities/utilities.js"></script>
    <script type="text/javascript" src="[{$shop->basetpldir}]yui/build/button/button-min.js"></script>
    <script type="text/javascript" src="[{$shop->basetpldir}]yui/build/slider/slider-min.js"></script>
    <script type="text/javascript" src="[{$shop->basetpldir}]yui/build/colorpicker/colorpicker-min.js"></script>
    <script type="text/javascript" src="[{$shop->basetpldir}]yui/build/container/container-min.js"></script>
    <script type="text/javascript" src="[{$shop->basetpldir}]yui/build/tabview/tabview-min.js"></script>
    <script type="text/javascript" src="[{$shop->basetpldir}]yui/build/treeview/treeview-min.js"></script>
    <script type="text/javascript" src="[{$shop->basetpldir}]yui/build/stylesheet/stylesheet-min.js"></script>

    <link rel="stylesheet" type="text/css" href="[{$shop->basetpldir}]gui.css">
    <script type="text/javascript" src="[{$shop->basetpldir}]yui/oxid-gui.js" ></script>

     <!--[if lt IE 7]>
     <style type="text/css">
        #gui-picker .yui-picker-bg {background-image: none;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='[{$shop->basetpldir}]yui/build/colorpicker/assets/picker_mask.png', sizingMethod='scale');}
     </style>
     <![endif]-->

     [{foreach from=$themes key=th item=title}]
     <style type="text/css" rel="alternate stylesheet" id="gui-th-[{$th}]" title="gui-th-[{$th}]-css">
            [{foreach from=$colors[$th] key=index item=color}]
            .gui-cl-[{$index}]{ background-color:[{$color}];}
            [{/foreach}]
     </style>
     [{/foreach}]

     <style type="text/css" id="gui-cl-css">
     [{foreach from=$user_colors key=id item=color}]
        .gui-cl-[{$id}]{background-color:[{$color}];}
     [{/foreach}]
     </style>

     <style type="text/css" id="gui-st-css">
     [{foreach from=$user_styles key=_const item=color}]
        .gui-st-[{$_const}]{background-color:[{$color}];}
     [{/foreach}]
     </style>

</head>

<body class="yui-skin-sam">

    <div id="gui-dialog" class="yui-picker-panel">
        <div class="hd">[{ oxmultilang ident="GUI_TITLE" }]</div>
        <div class="bd gui-dialog-bd">
        <form method="POST" action="assets/post.php">

            <div id="gui-tabs" class="yui-navset">
                <ul class="yui-nav">
                    <li class="selected"><a href="#logo"><em>[{ oxmultilang ident="GUI_TAB_THEMES" }]</em></a></li>
                    <li><a href="#colors"><em>[{ oxmultilang ident="GUI_TAB_COLORS" }]</em></a></li>
                    <li><a href="#styles"><em>[{ oxmultilang ident="GUI_TAB_STYLES" }]</em></a></li>
                </ul>

                <div class="yui-content">

                    <div id="gui-tab-themes">
                        <table width="100%">
                        [{foreach from=$themes key=th item=title}]
                        <tr>
                            <td><label><input type="radio" value="[{$th}]" name="t" [{if $theme == $th}]checked[{/if}] onclick="gui.setTheme(this.value)"> <b>[{$title}]</b></label></td>
                            [{foreach from=$colors[$th] key=index item=color}]
                            <td style="background:[{$color}];">&nbsp;</td>
                            [{/foreach}]
                        </tr>
                        [{/foreach}]
                        </table>
                    </div>

                    <div id="gui-tab-colors">
                        <ul id="gui-colors">
                            [{foreach from=$colors[$theme] key=id item=color}]
                            <li><b class="gui-cl-[{$id}]" id="gui-cl-[{$id}]-ico"><input id="gui-cl-[{$id}]" name="c[[{$id}]]" value="[{if $user_colors.$id }][{$user_colors.$id}][{else}][{$color}][{/if}]" onkeyup="gui.editColor(this.id);" onfocus="gui.editColor(this.id);" rel="gui-cl-[{$id}]-ico"></b></li>
                            [{/foreach}]
                        </ul>
                    </div>

                    <div id="gui-tab-styles">

                        <div id="gui-tree">
                        [{defun name="style_tree" _tree=$styles _id='gui-tree-src' _class=''}]
                            <ul class="[{$_class}]">
                            [{foreach from=$_tree index=id item=style}]
                                [{if $style->nodeType == XML_ELEMENT_NODE}]
                                <li>
                                [{strip}]
                                    [{assign var="_const" value=$style->getAttribute('const') }]
                                    [{assign var="_title" value=$style->getAttribute('title')|oxmultilangassign }]
                                    [{assign var="_index" value=$style->getAttribute('color') }]

                                    [{if $colorstyles[$_const]}]
                                        [{assign var="_index" value=$colorstyles[$_const] }]
                                    [{/if}]

                                    [{assign var="_value" value=$colors[$theme][$_index] }]

                                    [{if $style->childNodes->length}]
                                        [{ $_title }]
                                        [{fun name="style_tree" _tree=$style->childNodes _id="" _class=""}]
                                    [{else}]
                                        <span>
                                            <b class="gui-cl-[{$_index}] gui-st-[{$_const}]" id="gui-st-[{$_const}]-ico" title="gui-st-[{$_const}]"></b>
                                            [{capture name="styles"}]
                                                [{$smarty.capture.styles}]
                                                <input name="s[[{$_const}]]" id="gui-st-[{$_const}]" value="[{if $user_styles.$_const}][{$user_styles.$_const}][{else}][{$_value}][{/if}]" rel="gui-cl-[{$_index}]-ico" type="hidden">
                                            [{/capture}]
                                            [{ $_title }]
                                        </span>
                                    [{/if}]
                                [{/strip}]
                                </li>
                                [{/if}]
                            [{/foreach}]
                            </ul>
                        [{/defun}]
                        </div>

                    </div>
                </div>
            </div>

            [{$smarty.capture.styles}]

            </form>

            <div class="yui-picker" id="gui-picker"></div>

        </div>
        <div class="ft"></div>
    </div>

    <script type="text/javascript">

        var gui;

        YAHOO.util.Event.onDOMReady(function() {
            gui = new YAHOO.oxid.gui( 'gui-dialog', 'gui-tabs', 'gui-picker', 'gui-tree', 'gui-preview', '[{ $shop->selflink }]', '[{ $shop->basetpldir }]');
            gui.render();
            gui.show();
        });
    </script>

    <script type="text/javascript">
    if (parent.parent)
    {   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
        parent.parent.sMenuItem    = "[{ oxmultilang ident="GUI_LIST_MENUITEM" }]";
        parent.parent.sMenuSubItem = "[{ oxmultilang ident="GUI_LIST_MENUSUBITEM" }]";
        parent.parent.sWorkArea    = "[{$_act}]";
        parent.parent.setTitle();
    }
    </script>

    <iframe id="gui-preview" name="preview" src="[{$shop->currenthomedir}]" width="100%" height="99%" frameborder="no">

</body>
</html>