<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title><?php echo( $aLang['HEADER_META_MAIN_TITLE'] ) ?> - <?php echo( $title ) ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo( $aLang['charset'] ) ?>">
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
    <style type="text/css">
    <?php
        $iTabWidth = 147;
        $iSepWidth = 3;
            $iTabCount = 6;
            $sHColor = '#ff3600';
        $iDocWidth = ($iTabWidth + $iSepWidth)*$iTabCount;
    ?>
        body, p , form {margin:0; }
        body, p, td, tr, ol, ul, input, textarea {font:11px/130% Trebuchet MS, Tahoma, Verdana, Arial, Helvetica, sans-serif;}

        a {text-decoration: none;color: #000;}
        a:hover {text-decoration: underline;}

        #page {width:<?php echo $iDocWidth; ?>px;margin:5% auto;}
        #header {clear:both;margin-top:10px;}
        #body   {clear:both;padding:20px 10px;background: #e4e4e4 url(setup.png) 0 -80px repeat-x;border:1px solid #ccc;border-top:none;margin:-10px 1px 0 0;min-height: 350px;}
        #footer {clear:both;background:#888;color:#fff;padding:5px 10px;margin-right:1px;}

        dl.tab {float:left;width: <?php echo $iTabWidth; ?>px;height:80px;margin:0;margin-right:1px;background:#ccc url(setup.png);border:1px solid #ccc;border-bottom:none;margin-bottom:-1px;}
        dl.tab dt{display:block;padding:0;margin:0;padding:10px 5px 0 5px;font-weight: bold;}
        dl.tab a{color:#888;}
        dl.tab dd{display:block;padding:0;margin:0;padding:5px;height: 50px;}

        dl.tab.act {border-color:<?php echo $sHColor; ?>;}
        dl.tab.act dt a{color: <?php echo $sHColor; ?>;}
        dl.tab.act dd{}
        dl.tab.act dd a{color: #000;}

        ul.req {padding:0 5px;border:1px solid #888;margin:5px 0;clear:both;display:block;}
        ul.req li{list-style:none;margin:5px 0;border-left:14px solid gray;padding-left:.5em;}
        ul.req li.pass{border-color:green;}
        ul.req li.pmin{border-color:orange;}
        ul.req li.fail{border-color:red;}
        ul.req li.null{border-color:gray;}
        ul.req ul{padding:0;margin:0;}
        ul.req li.group {border:none;float:left;font-weight:bold;width:32%;}
        ul.req li.clear{clear:left;diplay:none;border:none;visibility:collapse;height:0px;padding:0;margin:0;display:block;line-height: 0;}
    </style>

    <?php
        $sImagDir = '../out/admin/img';

        if ( isset( $iRedir2Step) && $iRedir2Step ){
            echo( '<meta http-equiv="refresh" content="3; URL=index.php?istep='.$iRedir2Step.'&sid='.getSID().'">');
        }
    ?>
</head>

<body>

<div id="page">
    <a href="index.php?istep=<?php echo $aSetupSteps['STEP_SYSTEMREQ']; ?>&sid=<?php echo( getSID()); ?>"><img src="<?php echo $sImagDir; ?>/setup_logo.gif" alt="OXID eSales" hspace="5" vspace="5" border="0"></a>
    <div id="header">
        <?php
        $iCntr = 0;
        foreach ( $aSetupSteps as $_tab ) {

            // only "real" steps
            if ( fmod( $_tab, 100 ) ) {
                continue;
            }

            $blAct = ( floor( $istep / 100 ) == ( $_tab / 100 ) );
            $iStepId = floor( $_tab / 100 ) - 1;
            $iCntr++;
        ?>
        <dl class="tab <?php if( $blAct ){ echo "act";} ?>">
            <dt><?php if( $blAct ): ?><a href="index.php?istep=<?php echo $_tab; ?>&sid=<?php echo getSID();?>"><?php endif;?><?php echo $iCntr ,'. ',$aLang['TAB_'.$iStepId.'_TITLE']; ?><?php if( $blAct ): ?></a><?php endif;?></dt>
            <dd><?php if( $blAct ): ?><a href="index.php?istep=<?php echo $_tab; ?>&sid=<?php echo getSID();?>"><?php endif;?><?php echo $aLang['TAB_'.$iStepId.'_DESC'] ?><?php if( $blAct ): ?></a><?php endif;?></dd>
        </dl>
        <?php } ?>
    </div>

    <div id="body">
    <?php

    if ( isset( $sMessage) && $sMessage) {
        echo "<br><b>$sMessage</b>";
    }

    if ( isset( $iRedir2Step) && $iRedir2Step) {
        echo( "<br><br>" . $aLang['HEADER_TEXT_SETUP_NOT_RUNS_AUTOMATICLY'] . " " );
         echo( '<a href="index.php?istep='.$iRedir2Step.'&sid='.getSID().'" id="continue"><b>' . $aLang['HERE'] . '</b></a>.<br><br>');
    }
