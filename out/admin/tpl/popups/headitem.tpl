<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <title>[{ $title }]</title>
  <meta http-equiv="Content-Type" content="text/html; charset=[{$charset}]">
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
  <link rel="stylesheet" href="[{$shop->basetpldir}]style.css">



[{ assign var="sYuiUrl" value="`$shop->basetpldir`yui/build/" }]

<!-- css -->
<link rel="stylesheet" type="text/css" href="[{ $sYuiUrl }]assets/skins/sam/skin.css">
<!-- js -->

<script type="text/javascript" src="[{ $sYuiUrl }]yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="[{ $sYuiUrl }]dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="[{ $sYuiUrl }]container/container-min.js"></script>
<script type="text/javascript" src="[{ $sYuiUrl }]element/element-beta-min.js"></script>
<script type="text/javascript" src="[{ $sYuiUrl }]datasource/datasource-min.js"></script>
<script type="text/javascript" src="[{ $sYuiUrl }]datatable/datatable-min.js"></script>
<script type="text/javascript" src="[{ $sYuiUrl }]json/json-min.js"></script>
<script type="text/javascript" src="[{ $sYuiUrl }]menu/menu-min.js"></script>
<script type="text/javascript" src="[{ $sYuiUrl }]connection/connection-min.js"></script>
[{*<script type="text/javascript" src="[{ $sYuiUrl }]logger/logger-min.js"></script>*}]

<script type="text/javascript" src="[{ $shop->basetpldir }]yui/oxid-aoc.js"></script>

<style type="text/css">
    body {
        background-color: #E7EAED;
        margin: 15px;
        font-size:11px;
        height: 100%;
    }
    input {
        font-size:11px;
    }
    .oxid-aoc {
        border:1px solid #E7EAED;
        background: #E7EAED;
        width:100%;
        height:100%;
    }

    .oxid-aoc-table, div.yui-dt-bd table {
        background-color: #ffffff;
        width: 100%;
        height: 100%;
        border: 1px solid #7F7F7F;
        border-right: 1px solid #CBCBCB;
    }

    table.yui-dt-table {
        width:100%;
    }

    .oxid-aoc-table .yui-dt-table tbody{
        margin: 11px;
    }

    .oxid-aoc-scrollbar {
        float: right;
        width: 20px;
        height: 100%;
        overflow: auto;
    }

    .oxid-aoc-scrollbar div {
        width:2px;
    }

    .oxid-aoc-table .yui-dt-table td,
    .oxid-aoc-table .yui-dt-table th{
        overflow:hidden;
        padding:2px 10px !important;
        white-space:nowrap;
        font-size:11px;
    }

    .oxid-aoc-table .yui-dt-table th input{
        font-size: 10px;
        width: 100%;
    }

    .ddtarget, .ddtarget td{
        background-color: #ccc !important;
    }

    .oxid-aoc-hidden-col {
        display:none;
    }
    .oxid-aoc-primary-cat {
        background-color: #0099ff !important;
        color: #ffffff;
    }

    .yui-dt-col-_0 div,
    .yui-dt-col-_1 div,
    .yui-dt-col-_2 div,
    .yui-dt-col-_3 div,
    .yui-dt-col-_4 div {
        overflow: hidden;
    }
    #container1, #container2, #container3 {
        height:100%;
    }
    #container1_c, #container2_c, #container3_c {
        margin-right: -1px;
    }
    div.yui-dt-bd table, .yui-dt-table {
        border: 0 !important;
    }
    div.yui-dt-bd table, .yui-dt-table tbody  {
        border-left: 0 !important;
        border-bottom: 0 !important;
        border-right: 0 !important;
    }
    div.yui-dt-bd table, .yui-dt-table thead  {
        border-left: 0 !important;
        border-right: 0 !important;
    }
    .oxid-aoc-button {
        padding:2px 10px;
    }

    .yui-dt-liner {
        padding:2px 10px !important;
        overflow: hidden !important;
        height: 14px;
    }

    .yui-dt-hidden {
        width: 0px !important;
        display: none !important;
    }

    .yui-dt-resizeable, .yui-dt-sortable, .oxid-aoc-table .yui-dt-table td, .oxid-aoc-table .yui-dt-table th {
        margin: 0px !important;
        padding: 0px !important;
    }

</style>

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

</head>
<body class="yui-skin-sam">
[{include file="inc_error.tpl" Errorlist=$Errors.default}]
