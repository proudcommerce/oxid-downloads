<?php
/**
 *    This file is part of OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.oxid-esales.com
 * @package admin
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: report_canceled_orders.php 16302 2009-02-05 10:18:49Z rimvydas.paskevicius $
 */

if ( !class_exists( 'report_canceled_orders' ) ) {
    /**
     * Canceled orders reports class
     * @package admin
     */
    class Report_canceled_orders extends report_base
    {
        /**
         * Name of template to render
         *
         * @return string
         */
        protected $_sThisTemplate = "report_canceled_orders.tpl";

        /**
         * Collects and renders visitor/month report data
         *
         * @return null
         */
        public function visitor_month()
        {
            $myConfig = $this->getConfig();

            $aDataX = array();
            $aDataY = array();

            $dTimeTo    = strtotime( oxConfig::getParameter( "time_to"));
            $sTime_to   = date( "Y-m-d H:i:s", $dTimeTo);
            $dTimeFrom  = mktime( 23, 59, 59, date( "m", $dTimeTo)-12, date( "d", $dTimeTo), date( "Y", $dTimeTo));
            $sTime_from = date( "Y-m-d H:i:s", $dTimeFrom);


            $sSQL = "select oxtime, count(*) as nrof from oxlogs where oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            //die($sSQL);
            $aTemp = array();
            for ( $i = 1; $i <= 12; $i++)
                $aTemp[date( "m/Y", mktime(23, 59, 59, date( "m", $dTimeFrom)+$i, date( "d", $dTimeFrom), date( "Y", $dTimeFrom)) )] = 0;

            $rs = oxDb::getDb()->execute( $sSQL);
            $blData = false;
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $aTemp[date( "m/Y", strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                    $blData = true;
                }
            }

            $aDataX2  = array();
            $aDataX3  = array();
            if ($blData) {
                foreach ( $aTemp as $key => $value) {
                    $aDataX[$key]   = $value;
                    $aDataX2[$key]  = 0;
                    $aDataX3[$key]  = 0;
                    $aDataX4[$key]  = 0;
                    $aDataX5[$key]  = 0;
                    $aDataX6[$key]  = 0;
                    $aDataY[]       = $key;
                }
            }
            // collects sessions what executed 'order' function
            $sSQL = "select oxtime, oxsessid from `oxlogs` where oxclass = 'order' and oxfnc = 'execute' and oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            $aTempOrder = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $aTempOrder[$rs->fields[1]] = $rs->fields[0];
                    $rs->moveNext();
                }
            }

            // collects sessions what executed order class
            $sSQL = "select oxtime, oxsessid from `oxlogs` where oxclass = 'order' and oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            $aTempExecOrders = array();
            $aTempExecOrdersSessions = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if (!isset($aTempOrder[$rs->fields[1]])) {
                        $aTempExecOrdersSessions[$rs->fields[1]] = 1;
                        $aTempExecOrders[date( "m/Y", strtotime( $rs->fields[0]))]++;
                    }
                    $rs->moveNext();
                }
            }

            foreach ( $aTempExecOrders as $key => $value) {
                if (isset($aDataX6[$key]))
                    $aDataX6[$key] = $value;
            }

            // collects sessions what executed payment class
            $sSQL = "select oxtime, oxsessid from `oxlogs` where oxclass = 'payment' and oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            $aTempPayment = array();
            $aTempPaymentSessions = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if (!isset($aTempOrder[$rs->fields[1]]) && !isset($aTempExecOrdersSessions[$rs->fields[1]])) {
                        $aTempPaymentSessions[$rs->fields[1]] = 1;
                        $aTempPayment[date( "m/Y", strtotime( $rs->fields[0]))]++;
                    }
                    $rs->moveNext();
                }
            }

            foreach ( $aTempPayment as $key => $value) {
                if (isset($aDataX2[$key]))
                    $aDataX2[$key] = $value;
            }

            // collects sessions what executed 'user' class
            $sSQL = "select oxtime, oxsessid from `oxlogs` where oxclass = 'user' and oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            $aTempUser = array();
            $aTempUserSessions = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if (!isset($aTempOrder[$rs->fields[1]]) && !isset($aTempPaymentSessions[$rs->fields[1]]) && !isset($aTempExecOrdersSessions[$rs->fields[1]])) {
                        $aTempUserSessions[$rs->fields[1]] = 1;
                        $aTempUser[date( "m/Y", strtotime( $rs->fields[0]))]++;
                    }
                    $rs->moveNext();
                }
            }

            foreach ( $aTempUser as $key => $value) {
                if (isset($aDataX3[$key]))
                    $aDataX3[$key] = $value;
            }

            // collects sessions what executed 'tobasket' function
            $sSQL = "select oxtime, oxsessid from `oxlogs` where oxclass = 'basket' and oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            $aTempBasket = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if (!$aTempOrder[$rs->fields[1]] && !isset($aTempPaymentSessions[$rs->fields[1]]) && !isset($aTempUserSessions[$rs->fields[1]]) && !isset($aTempExecOrdersSessions[$rs->fields[1]]))
                        $aTempBasket[date( "m/Y", strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                }
            }

            foreach ( $aTempBasket as $key => $value) {
                if (isset($aDataX4[$key]))
                    $aDataX4[$key] = $value;
            }

            // orders made
            $sSQL = "select oxorderdate from oxorder where oxorderdate >= '$sTime_from' and oxorderdate <= '$sTime_to' order by oxorderdate";
            $aTemp = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $aTemp[date( "m/Y", strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                }
            }

            foreach ( $aTemp as $key => $value) {
                if (isset($aDataX5[$key]))
                    $aDataX5[$key] = $value;
            }

            header ("Content-type: image/png" );

            // New graph with a drop shadow
            $graph = new Graph(800, 600);

            $graph->setBackgroundImage( $myConfig->getAbsAdminImageDir()."/reportbgrnd.jpg", BGIMG_FILLFRAME);

            // Use a "text" X-scale
            $graph->setScale("textlin");

            // Label align for X-axis
            $graph->xaxis->setLabelAlign('center', 'top', 'right');

            // Label align for Y-axis
            $graph->yaxis->setLabelAlign('right', 'bottom');

            $graph->setShadow();
            // Description
            $graph->xaxis->setTickLabels( $aDataY);


            // Set title and subtitle
            $graph->title->set("Monat");

            // Use built in font
            $graph->title->setFont(FF_FONT1, FS_BOLD);

            $aDataFinalX2 = array();
            foreach ( $aDataX2 as $dData)
                $aDataFinalX2[] = $dData;

            // Create the bar plot
            $bplot2 = new BarPlot( $aDataFinalX2);
            $bplot2->setFillColor ("#9966cc");
            //$bplot2->setLegend("Kaeufer");
            $bplot2->setLegend("Best.Abbr. in Bezahlmethoden");

            $aDataFinalX3 = array();
            foreach ( $aDataX3 as $dData)
                $aDataFinalX3[] = $dData;

            // Create the bar plot
            $bplot3 = new BarPlot( $aDataFinalX3);
            $bplot3->setFillColor ("#ffcc00");
            $bplot3->setLegend("Best.Abbr. in Benutzer");

            $aDataFinalX4 = array();
            foreach ( $aDataX4 as $dData)
                $aDataFinalX4[] = $dData;

            // Create the bar plot
            $bplot4 = new BarPlot( $aDataFinalX4);
            $bplot4->setFillColor ("#6699ff");
            $bplot4->setLegend("Best.Abbr. in Warenkorb");

            $aDataFinalX6 = array();
            foreach ( $aDataX6 as $dData)
                $aDataFinalX6[] = $dData;

            // Create the bar plot
            $bplot6 = new BarPlot( $aDataFinalX6);
            $bplot6->setFillColor ("#ff0099");
            $bplot6->setLegend("Best.Abbr. in Bestellbestaetigung");

            $aDataFinalX5 = array();
            foreach ( $aDataX5 as $dData)
                $aDataFinalX5[] = $dData;

            // Create the bar plot
            $bplot5 = new BarPlot( $aDataFinalX5);
            $bplot5->setFillColor ("silver");
            $bplot5->setLegend("Bestellungen");

            // Create the grouped bar plot
            $gbplot = new groupBarPlot (array($bplot4, $bplot3, $bplot2, $bplot6, $bplot5));
            $graph->add( $gbplot);

            // Finally output the  image
            $graph->stroke();

        }

        /**
         * Collects and renders visitor/week report data
         *
         * @return null
         */
        public function visitor_week()
        {
            $myConfig = $this->getConfig();

            $aDataX = array();
            $aDataY = array();

            $dTimeTo    = strtotime( oxConfig::getParameter( "time_to"));
            $sTime_to   = date( "Y-m-d H:i:s", $dTimeTo);
            $dTimeFrom  = strtotime( oxConfig::getParameter( "time_from"));
            $sTime_from = date( "Y-m-d H:i:s", $dTimeFrom);

            $sSQL = "select oxtime, count(*) as nrof from oxlogs where oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid order by oxtime";

            $aTemp = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            $blData = false;
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $aTemp[oxUtilsDate::getInstance()->getWeekNumber($myConfig->getConfigParam( 'iFirstWeekDay' ), strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                    $blData = true;
                }
            }

            $aDataX2  = array();
            $aDataX3  = array();
            if ($blData) {
                foreach ( $aTemp as $key => $value) {
                    $aDataX[$key]   = $value;
                    $aDataX2[$key]  = 0;
                    $aDataX3[$key]  = 0;
                    $aDataX4[$key]  = 0;
                    $aDataX5[$key]  = 0;
                    $aDataX6[$key]  = 0;
                    $aDataY[]       = "KW ".$key;
                }
            }
            // collects sessions what executed 'order' function
            $sSQL = "select oxtime, oxsessid FROM `oxlogs` where oxclass = 'order' and oxfnc = 'execute' and oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            $aTempOrder = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $aTempOrder[$rs->fields[1]] = $rs->fields[0];
                    $rs->moveNext();
                }
            }

            // collects sessions what executed order class
            $sSQL = "select oxtime, oxsessid from `oxlogs` where oxclass = 'order' and oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            $aTempExecOrders = array();
            $aTempExecOrdersSessions = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if (!isset($aTempOrder[$rs->fields[1]])) {
                        $aTempExecOrdersSessions[$rs->fields[1]] = 1;
                        //$aTempExecOrders[date( "W", strtotime( $rs->fields[0]))]++;
                        $aTempExecOrders[oxUtilsDate::getInstance()->getWeekNumber($myConfig->getConfigParam( 'iFirstWeekDay' ), strtotime( $rs->fields[0]))]++;
                    }
                    $rs->moveNext();
                }
            }

            foreach ( $aTempExecOrders as $key => $value) {
                if (isset($aDataX6[$key]))
                    $aDataX6[$key] = $value;
            }

            // collects sessions what executed payment class
            $sSQL = "select oxtime, oxsessid from `oxlogs` where oxclass = 'payment' and oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            $aTempPayment = array();
            $aTempPaymentSessions = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if (!isset($aTempOrder[$rs->fields[1]]) && !isset($aTempExecOrdersSessions[$rs->fields[1]])) {
                        $aTempPaymentSessions[$rs->fields[1]] = 1;
                        //$aTempPayment[date( "W", strtotime( $rs->fields[0]))]++;
                        $aTempPayment[oxUtilsDate::getInstance()->getWeekNumber($myConfig->getConfigParam( 'iFirstWeekDay' ), strtotime( $rs->fields[0]))]++;
                    }
                    $rs->moveNext();
                }
            }

            foreach ( $aTempPayment as $key => $value) {
                if (isset($aDataX2[$key]))
                    $aDataX2[$key] = $value;
            }

            // collects sessions what executed 'user' class
            $sSQL = "select oxtime, oxsessid from `oxlogs` where oxclass = 'user' and oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            $aTempUser = array();
            $aTempUserSessions = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if (!isset($aTempOrder[$rs->fields[1]]) && !isset($aTempPaymentSessions[$rs->fields[1]]) && !isset($aTempExecOrdersSessions[$rs->fields[1]])) {
                        $aTempUserSessions[$rs->fields[1]] = 1;
                        //$aTempUser[date( "W", strtotime( $rs->fields[0]))]++;
                        $aTempUser[oxUtilsDate::getInstance()->getWeekNumber($myConfig->getConfigParam( 'iFirstWeekDay' ), strtotime( $rs->fields[0]))]++;
                    }
                    $rs->moveNext();
                }
            }

            foreach ( $aTempUser as $key => $value) {
                if (isset($aDataX3[$key]))
                    $aDataX3[$key] = $value;
            }

            // collects sessions what executed 'tobasket' function
            $sSQL = "select oxtime, oxsessid from `oxlogs` where oxclass = 'basket' and oxtime >= '$sTime_from' and oxtime <= '$sTime_to' group by oxsessid";
            $aTempBasket = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    if (!$aTempOrder[$rs->fields[1]] && !isset($aTempPaymentSessions[$rs->fields[1]]) && !isset($aTempUserSessions[$rs->fields[1]]) && !isset($aTempExecOrdersSessions[$rs->fields[1]]))
                        $aTempBasket[oxUtilsDate::getInstance()->getWeekNumber($myConfig->getConfigParam( 'iFirstWeekDay' ), strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                }
            }

            foreach ( $aTempBasket as $key => $value) {
                if (isset($aDataX4[$key]))
                    $aDataX4[$key] = $value;
            }

            // orders made
            $sSQL = "select oxorderdate from oxorder where oxorderdate >= '$sTime_from' and oxorderdate <= '$sTime_to' order by oxorderdate";
            $aTemp = array();
            $rs = oxDb::getDb()->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    //$aTemp[date( "W", strtotime( $rs->fields[0]))]++;
                    $aTemp[oxUtilsDate::getInstance()->getWeekNumber($myConfig->getConfigParam( 'iFirstWeekDay' ), strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                }
            }

            foreach ( $aTemp as $key => $value) {
                if (isset($aDataX5[$key]))
                    $aDataX5[$key] = $value;
            }


            header ("Content-type: image/png" );

            // New graph with a drop shadow
            $graph = new Graph( max( 800, count( $aDataX) * 80), 600);

            $graph->setBackgroundImage( $myConfig->getAbsAdminImageDir()."/reportbgrnd.jpg", BGIMG_FILLFRAME);

            // Use a "text" X-scale
            $graph->setScale("textlin");

            // Label align for X-axis
            $graph->xaxis->setLabelAlign('center', 'top', 'right');

            // Label align for Y-axis
            $graph->yaxis->setLabelAlign('right', 'bottom');

            $graph->setShadow();
            // Description
            $graph->xaxis->setTickLabels( $aDataY);


            // Set title and subtitle
            $graph->title->set("Woche");

            // Use built in font
            $graph->title->setFont(FF_FONT1, FS_BOLD);

            $aDataFinalX2 = array();
            foreach ( $aDataX2 as $dData)
                $aDataFinalX2[] = $dData;

            // Create the bar plot
            $bplot2 = new BarPlot( $aDataFinalX2);
            $bplot2->setFillColor ("#9966cc");
            $bplot2->setLegend("Best.Abbr. in Bezahlmethoden");

            $aDataFinalX3 = array();
            foreach ( $aDataX3 as $dData)
                $aDataFinalX3[] = $dData;

            // Create the bar plot
            $bplot3 = new BarPlot( $aDataFinalX3);
            $bplot3->setFillColor ("#ffcc00");
            $bplot3->setLegend("Best.Abbr. in Benutzer");

            $aDataFinalX4 = array();
            foreach ( $aDataX4 as $dData)
                $aDataFinalX4[] = $dData;

            // Create the bar plot
            $bplot4 = new BarPlot( $aDataFinalX4);
            $bplot4->setFillColor ("#6699ff");
            $bplot4->setLegend("Best.Abbr. in Warenkorb");

            $aDataFinalX6 = array();
            foreach ( $aDataX6 as $dData)
                $aDataFinalX6[] = $dData;

            // Create the bar plot
            $bplot6 = new BarPlot( $aDataFinalX6);
            $bplot6->setFillColor ("#ff0099");
            $bplot6->setLegend("Best.Abbr. in Bestellbestaetigung");

            $aDataFinalX5 = array();
            foreach ( $aDataX5 as $dData)
                $aDataFinalX5[] = $dData;

            // Create the bar plot
            $bplot5 = new BarPlot( $aDataFinalX5);
            $bplot5->setFillColor ("silver");
            $bplot5->setLegend("Bestellungen");

            // Create the grouped bar plot
            $gbplot = new groupBarPlot (array($bplot4, $bplot3, $bplot2, $bplot6, $bplot5));
            $graph->add( $gbplot);

            // Finally output the  image
            $graph->stroke();
        }
    }
}