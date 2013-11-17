<?php
eval( 'class mysql_oxadminlog_EXTENDER extends '. $last_module . '_ADOConnection { }' );
class oxadminlog_ADOConnection extends mysql_oxadminlog_EXTENDER
{
    public function &Execute( $sql, $inputarr = false )
    {
        $sSearch = strtolower( $sql );
        if ( ( strpos( $sSearch, 'insert into' ) !== false || strpos( $sSearch, 'update ' ) !== false ) &&
              strpos( $sSearch, 'oxsession' ) === false && strpos( $sSearch, 'oxadminlog' ) === false ) {

            $blInsert = true;

            $myConfig = oxConfig::getInstance();
            if ( is_array( $aLogSkipTags = $myConfig->getConfigParam( 'aLogSkipTags' ) ) ) {
                foreach( $aLogSkipTags as $sSkipThis ) {
                    if( strpos( $sSearch, $sSkipThis ) !== false ) {
                       $blInsert = false;
                       break;
                    }
                }
            }

            if ( $blInsert && $sSearch ) {

                $oUser = oxNew( 'oxuser' );
                if ( $oUser->loadAdminUser() ) {
                    $sUserId = $oUser->getId();
                } else {
                    $sUserId = '';
                }


                    $sQ = "insert into oxadminlog ( oxuserid, oxsql )
                           values ( '{$sUserId}', " . $this->quote( $sSearch ) . " ) ";

                // write information
                parent::Execute( $sQ );
            }
        }
        return parent::Execute( $sql, $inputarr );
    }
}

eval( 'class mysql_oxadminlog_resultset_EXTENDER extends '. $last_module . '_ResultSet { }' );
class oxadminlog_ResultSet extends mysql_oxadminlog_resultset_EXTENDER {}