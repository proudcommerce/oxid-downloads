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
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * $Id: report_conversion_rate.php 23188 2009-10-13 06:59:38Z sarunas $
 */

if ( !class_exists( "report_conversion_rate")) {
    /**
     * Conversion rate reports class
     * @package admin
     */
    class Report_conversion_rate extends report_base
    {
        /**
         * Name of template to render
         *
         * @return string
         */
        protected $_sThisTemplate = "report_conversion_rate.tpl";

        /**
         * Checks if db contains data for report generation
         *
         * @return bool
         */
        public function drawReport()
        {
            $oDb = oxDb::getDb();

            $oSmarty    = $this->getSmarty();
            $sTime_from = $oDb->quote( date( "Y-m-d H:i:s", strtotime( $oSmarty->_tpl_vars['time_from'] ) ) );
            $sTime_to   = $oDb->quote( date( "Y-m-d H:i:s", strtotime( $oSmarty->_tpl_vars['time_to'] ) ) );

            // orders
            if ( $oDb->getOne( "select * from oxlogs where oxtime >= $sTime_from and oxtime <= $sTime_to" ) ) {
                return true;
            }

            // orders
            if ( $oDb->getOne( "select 1 from oxorder where oxorderdate >= $sTime_from and oxorderdate <= $sTime_to" ) ) {
                return true;
            }
        }

        /**
         * Collects and renders visitor/month report data
         *
         * @return null
         */
        public function visitor_month()
        {
            $myConfig = $this->getConfig();
            $oDb = oxDb::getDb();

            $aDataX = array();
            $aDataY = array();

            $dTimeTo    = strtotime( oxConfig::getParameter( "time_to"));
            $sTime_to   = $oDb->quote( date( "Y-m-d H:i:s", $dTimeTo ) );
            $dTimeFrom  = mktime( 23, 59, 59, date( "m", $dTimeTo)-12, date( "d", $dTimeTo), date( "Y", $dTimeTo));
            $sTime_from = $oDb->quote( date( "Y-m-d H:i:s", $dTimeFrom ) );

            // orders
            $sSQL = "select oxtime, count(*) as nrof from oxlogs where oxtime >= $sTime_from and oxtime <= $sTime_to group by oxsessid";
            $aTemp = array();
            for ( $i = 1; $i <= 12; $i++)
                $aTemp[date( "m/Y", mktime( 23, 59, 59, date( "m", $dTimeFrom)+$i, date( "d", $dTimeFrom), date( "Y", $dTimeFrom)) )] = 0;

            $rs = $oDb->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $aTemp[date( "m/Y", strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                }
            }

            $aDataX2  = array();
            $aDataX3  = array();

            foreach ( $aTemp as $key => $value) {
                $aDataX[$key]   = $value;
                $aDataX2[$key]  = 0;
                $aDataX3[$key]  = 0;
                $aDataY[]       = $key;
            }

            // orders
            $sSQL = "select oxorderdate from oxorder where oxorderdate >= $sTime_from and oxorderdate <= $sTime_to order by oxorderdate";
            $aTemp = array();
            $rs = $oDb->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    $aTemp[date( "m/Y", strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                }
            }

            foreach ( $aTemp as $key => $value) {
                if (isset($aDataX2[$key]))
                    $aDataX2[$key] = $value;
            }

            header ("Content-type: image/png" );

            // New graph with a drop shadow
            $graph = new Graph(800, 600, "auto");

            $graph->setBackgroundImage( $myConfig->getAbsAdminImageDir()."/reportbgrnd.jpg", BGIMG_FILLFRAME);

            // Use a "text" X-scale
            $graph->setScale("textlin");
            $graph->setY2Scale("lin");
            $graph->y2axis->setColor("red");

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

            $aDataFinalX = array();
            foreach ( $aDataX as $dData)
                $aDataFinalX[] = $dData;

            // Create the bar plot
            $l2plot=new LinePlot($aDataFinalX);
            $l2plot->setColor("navy");
            $l2plot->setWeight(2);
            $l2plot->setLegend("Besucher");
            //$l1plot->SetBarCenter();
            $l2plot->value->setColor("navy");
            $l2plot->value->setFormat('% d');
            $l2plot->value->hideZero();
            $l2plot->value->show();

            $aDataFinalX2 = array();
            foreach ( $aDataX2 as $dData)
                $aDataFinalX2[] = $dData;

            // Create the bar plot
            $l3plot=new LinePlot($aDataFinalX2);
            $l3plot->setColor("orange");
            $l3plot->setWeight(2);
            $l3plot->setLegend("Bestellungen");
            //$l1plot->SetBarCenter();
            $l3plot->value->setColor('orange');
            $l3plot->value->setFormat('% d');
            $l3plot->value->hideZero();
            $l3plot->value->show();

            //conversion rate graph
            $l1datay = array();
            for ($iCtr = 0; $iCtr < count($aDataFinalX); $iCtr++) {
                if ($aDataFinalX[$iCtr] != 0 && $aDataFinalX2[$iCtr] != 0) {
                    $l1datay[] = 100/($aDataFinalX[$iCtr]/$aDataFinalX2[$iCtr]);
                } else
                    $l1datay[] = 0;
            }

            $l1plot=new LinePlot($l1datay);
            $l1plot->setColor("red");
            $l1plot->setWeight(2);
            $l1plot->setLegend("Conversion rate (%)");
            $l1plot->value->setColor('red');
            $l1plot->value->setFormat('% 0.2f%%');
            $l1plot->value->hideZero();
            $l1plot->value->show();

            // Create the grouped bar plot1
            $graph->addY2( $l1plot );
            $graph->add( $l2plot );
            $graph->add( $l3plot );

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
            $oDb = oxDb::getDb();

            $aDataX = array();
            $aDataX2  = array();
            $aDataX3  = array();
            $aDataY = array();

            $dTimeTo    = strtotime( oxConfig::getParameter( "time_to"));
            $sTime_to   = $oDb->quote( date( "Y-m-d H:i:s", $dTimeTo ) );
            $dTimeFrom  = strtotime( oxConfig::getParameter( "time_from"));
            $sTime_from = $oDb->quote( date( "Y-m-d H:i:s", $dTimeFrom ) );

            $sSQL = "select oxtime, count(*) as nrof from oxlogs where oxtime >= $sTime_from and oxtime <= $sTime_to group by oxsessid order by oxtime";
            $aTemp = array();
            $rs = $oDb->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    //$aTemp[date( "W", strtotime( $rs->fields[0]))]++;
                    $aTemp[oxUtilsDate::getInstance()->getWeekNumber($myConfig->getConfigParam( 'iFirstWeekDay' ), strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                }
            }


            // initializing
            list( $iFrom, $iTo ) = $this->getWeekRange();
            for ( $i = $iFrom; $i < $iTo; $i++ ) {
                $aDataX[$i]  = 0;
                $aDataX2[$i] = 0;
                $aDataX3[$i] = 0;
                $aDataY[]    = "KW ".$i;
            }

            foreach ( $aTemp as $key => $value) {
                $aDataX[$key]   = $value;
                $aDataX2[$key]  = 0;
                $aDataX3[$key]  = 0;
                $aDataY[]       = "KW ".$key;
            }

            // buyer
            $sSQL = "select oxorderdate from oxorder where oxorderdate >= $sTime_from and oxorderdate <= $sTime_to order by oxorderdate";
            $aTemp = array();
            $rs = $oDb->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    //$aTemp[date( "W", strtotime( $rs->fields[0]))]++;
                    $aTemp[oxUtilsDate::getInstance()->getWeekNumber($myConfig->getConfigParam( 'iFirstWeekDay' ), strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                }
            }

            foreach ( $aTemp as $key => $value) {
                if (isset($aDataX2[$key]))
                    $aDataX2[$key] = $value;
            }

            // newcustomer
            $sSQL = "select oxtime, oxsessid from oxlogs where oxtime >= $sTime_from and oxtime <= $sTime_to group by oxsessid order by oxtime";
            $aTemp = array();
            $rs = $oDb->execute( $sSQL);
            if ($rs != false && $rs->recordCount() > 0) {
                while (!$rs->EOF) {
                    //$aTemp[date( "W", strtotime( $rs->fields[0]))]++;
                    $aTemp[oxUtilsDate::getInstance()->getWeekNumber($myConfig->getConfigParam( 'iFirstWeekDay' ), strtotime( $rs->fields[0]))]++;
                    $rs->moveNext();
                }
            }

            foreach ( $aTemp as $key => $value) {
                if (isset($aDataX3[$key]))
                    $aDataX3[$key] = $value;
            }

            header ("Content-type: image/png" );

            // New graph with a drop shadow
            $graph = new Graph( max( 800, count( $aDataX) * 80), 600);

            $graph->setBackgroundImage( $myConfig->getAbsAdminImageDir()."/reportbgrnd.jpg", BGIMG_FILLFRAME);

            // Use a "text" X-scale
            $graph->setScale("textlin");
            $graph->setY2Scale("lin");
            $graph->y2axis->setColor("red");

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

            $aDataFinalX = array();
            foreach ( $aDataX as $dData)
                $aDataFinalX[] = $dData;

            // Create the bar plot
            $l2plot=new LinePlot($aDataFinalX);
            $l2plot->setColor("navy");
            $l2plot->setWeight(2);
            $l2plot->setLegend("Besucher");
            $l2plot->value->setColor("navy");
            $l2plot->value->setFormat('% d');
            $l2plot->value->hideZero();
            $l2plot->value->show();

            $aDataFinalX2 = array();
            foreach ( $aDataX2 as $dData)
                $aDataFinalX2[] = $dData;

            // Create the bar plot
            $l3plot=new LinePlot($aDataFinalX2);
            $l3plot->setColor("orange");
            $l3plot->setWeight(2);
            $l3plot->setLegend("Bestellungen");
            //$l1plot->SetBarCenter();
            $l3plot->value->setColor("orange");
            $l3plot->value->setFormat('% d');
            $l3plot->value->hideZero();
            $l3plot->value->show();

            //conversion rate graph
            $l1datay = array();
            for ($iCtr = 0; $iCtr < count($aDataFinalX); $iCtr++) {
                if ($aDataFinalX[$iCtr] != 0 && $aDataFinalX2[$iCtr] != 0) {
                    $l1datay[] = 100/($aDataFinalX[$iCtr]/$aDataFinalX2[$iCtr]);
                } else
                    $l1datay[] = 0;
            }
            $l1plot=new LinePlot($l1datay);
            $l1plot->setColor("red");
            $l1plot->setWeight(2);
            $l1plot->setLegend("Conversion rate (%)");
            $l1plot->value->setColor('red');
            $l1plot->value->setFormat('% 0.4f%%');
            $l1plot->value->hideZero();
            $l1plot->value->show();

            // Create the grouped bar plot
            $graph->addY2($l1plot);
            $graph->add($l2plot);
            $graph->add($l3plot);

            // Finally output the  image
            $graph->stroke();
        }
    }
}