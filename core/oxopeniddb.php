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
 * @link      http://www.oxid-esales.com
 * @package   core
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: oxopeniddb.php 25467 2010-02-01 14:14:26Z alfonsas $
 */

require_once 'Auth/OpenID/Interface.php';
require_once 'Auth/OpenID/Nonce.php';

/**
 * Database connection class for openid
 */
class oxOpenIdDb extends Auth_OpenID_OpenIDStore
{
    /**
     * Associations table name
     *
     * @var string
     */
    protected $_sAssociationsTable = null;

    /**
     * Nonces table name
     *
     * @var string
     */
    protected $_sNoncesTable = null;

    /**
     * Nonces table name
     *
     * @var string
     */
    protected $_oDB = null;

    /**
     * Maximum nonce age
     *
     * @var integer
     */
    protected $_iMaxNonceAge = null;

    /**
     * Class constructor
     *
     * @return null
     */
    public function __construct()
    {
        $this->_sAssociationsTable = "oxoidassociations";
        $this->_sNoncesTable       = "oxoidnonces";
        $this->_oDB = oxDb::getDb();
        $this->_iMaxNonceAge = 6 * 60 * 60;
    }

    /**
     * This method puts an Association object into storage,
     * retrievable by server URL and handle.
     *
     * @param string $sServerUrl   the URL of the identity server
     * @param object $oAssociation the Association to store
     *
     * @return null
     */
    public function storeAssociation($sServerUrl, $oAssociation)
    {
        $sSql  = "REPLACE INTO " . $this->_sAssociationsTable . " (server_url,handle,secret,issued,lifetime,assoc_type)";
        $sSql .= " VALUES (".$this->_oDB->quote( $sServerUrl ).",".$this->_oDB->quote( $oAssociation->handle ).",'";
        $sSql .= $this->blobEncode($oAssociation->secret)."', ".$this->_oDB->quote( $oAssociation->issued ).", ";
        $sSql .= $this->_oDB->quote( $oAssociation->lifetime ).", ".$this->_oDB->quote( $oAssociation->assoc_type ).")";

        $this->_oDB->execute($sSql);
    }

    /**
     * This method removes the matching association if it's found, and
     * returns whether the association was removed or not.
     *
     * @param string $sServerUrl the URL of the identity server
     * @param string $sHandle    handle of the association to remove
     *
     * @return bool returns if given association exists
     */
    public function removeAssociation($sServerUrl, $sHandle)
    {
        $aRes = $this->_getAssoc($sServerUrl, $sHandle);
        if ( !$aRes ) {
            return false;
        }

        $sSql  = "DELETE FROM ".$this->_sAssociationsTable;
        $sSql .= " WHERE server_url = ".$this->_oDB->quote( $sServerUrl )." AND handle = ".$this->_oDB->quote( $sHandle );
        $this->_oDB->execute($sSql);

        return true;
    }

    /**
     * This method returns an Association object from storage that
     * matches the server URL and, if specified, handle. It returns
     * null if no such association is found or if the matching
     * association is expired.
     *
     * If no handle is specified, the store may return any association
     * which matches the server URL. If multiple associations are
     * valid, the recommended return value for this method is the one
     * most recently issued.
     *
     * @param string $sServerUrl the URL of the identity server
     * @param string $sHandle    handle of the association to remove
     *
     * @return object association for the given identity server.
     */
    public function getAssociation($sServerUrl, $sHandle = null)
    {
        $aAssociations = array();
        if ($sHandle !== null) {
            $aRes = $this->_getAssoc($sServerUrl, $sHandle);

            $aAssocs = array();
            if ( $aRes->recordCount() > 0 ) {
                $aAssocRow = $aRes->fields;
                $oAssoc = new Auth_OpenID_Association($aAssocRow['0'],
                                                 $aAssocRow['1'],
                                                 $aAssocRow['2'],
                                                 $aAssocRow['3'],
                                                 $aAssocRow['4']);
                $oAssoc->secret = $this->blobDecode($oAssoc->secret);

                if ($oAssoc->getExpiresIn() == 0) {
                    $this->removeAssociation($sServerUrl, $oAssoc->handle);
                } else {
                    $aAssociations[] = array($oAssoc->issued, $oAssoc);
                }
            } else {
                return null;
            }
        } else {
            $aAssocs = $this->_getAssocs($sServerUrl);
            if ( !$aAssocs || ( $aAssocs->recordCount() == 0 ) ) {
                return null;
            }
            $aAssocRow = $aAssocs->fields;
            $oAssoc = new Auth_OpenID_Association($aAssocRow['0'],
                                             $aAssocRow['1'],
                                             $aAssocRow['2'],
                                             $aAssocRow['3'],
                                             $aAssocRow['4']);
            $oAssoc->secret = $this->blobDecode($oAssoc->secret);
            if ($oAssoc->getExpiresIn() == 0) {
                $this->removeAssociation($sServerUrl, $oAssoc->handle);
            } else {
                $aAssociations[] = array($oAssoc->issued, $oAssoc);
            }
        }

        if ($aAssociations) {
            $aIssued = array();
            $aAssocs = array();
            foreach ($aAssociations as $key => $assoc) {
                $aIssued[$key] = $assoc[0];
                $aAssocs[$key] = $assoc[1];
            }

            array_multisort($aIssued, SORT_DESC, $aAssocs, SORT_DESC, $aAssociations);

            // return the most recently issued one.
            list($aIssued, $oAssoc) = $aAssociations[0];
            return $oAssoc;
        } else {
            return null;
        }

    }

    /**
     * Called when using a nonce.
     *
     * This method should return true if the nonce has not been
     * used before, and store it for a while to make sure nobody
     * tries to use the same value again.  If the nonce has already
     * been used, return false.
     *
     * @param string $sServerUrl the URL of the identity server
     * @param string $sTimestamp timestamp
     * @param string $sSalt      salt
     *
     * @return bool if nonce was valid.
     */
    public function useNonce($sServerUrl, $sTimestamp, $sSalt)
    {
        if ( abs($sTimestamp - time()) > $this->_iMaxNonceAge ) {
            return false;
        }
        $sSql = "INSERT INTO " . $this->_sNoncesTable . " (server_url, timestamp, salt) ";
        $sSql.= "VALUES (".$this->_oDB->quote( $sServerUrl ).", ".$this->_oDB->quote( $sTimestamp ).", ".$this->_oDB->quote( $sSalt ).")";
        $this->_oDB->execute($sSql);
        return true;
    }

    /**
     * Remove expired nonces from the store.
     *
     * @return null
     */
    public function cleanupNonces()
    {
        $v = time() - $this->_iMaxNonceAge;

        $sSql = "DELETE FROM " . $this->_sNoncesTable . " WHERE timestamp < " . $v;
        $this->_oDB->execute($sSql);
    }

    /**
     * Remove expired associations from the store.
     *
     * @return null
     */
    public function cleanupAssociations()
    {
        $sSql = "DELETE FROM " . $this->_sAssociationsTable . " WHERE issued + lifetime <" . time();
        $this->_oDB->execute($sSql);
    }

    /**
     * Resets the store by removing all records from the store's
     * tables.
     *
     * @return null
     */
    public function reset()
    {
        $this->_oDB->execute("DELETE FROM " . $this->_sAssociationsTable);
        $this->_oDB->execute("DELETE FROM " . $this->_sNoncesTable);
    }

    /**
     * Creates tables if not exists
     *
     * @return null
     */
    public function createTables()
    {
        $oRet = $this->_oDB->execute("show tables like '".$this->_sNoncesTable."'" );
        if ( $oRet->recordCount() == 0 ) {
            $sSqlNonces = "CREATE TABLE IF NOT EXISTS ".$this->_sNoncesTable." (";
            $sSqlNonces.= "server_url VARCHAR(2047) NOT NULL,";
            $sSqlNonces.= "timestamp INTEGER NOT NULL,";
            $sSqlNonces.= "salt CHAR(40) NOT NULL,";
            $sSqlNonces.= "UNIQUE (server_url(255), timestamp, salt)";
            $sSqlNonces.= ") ENGINE=InnoDB";
            $this->_oDB->execute($sSqlNonces);
        }
        $oRet = $this->_oDB->execute("show tables like '".$this->_sAssociationsTable."'" );
        if ( $oRet->recordCount() == 0 ) {
            $sSqlAssoc = "CREATE TABLE IF NOT EXISTS ".$this->_sAssociationsTable." (";
            $sSqlAssoc.= "server_url BLOB NOT NULL,";
            $sSqlAssoc.= "handle VARCHAR(255) NOT NULL,";
            $sSqlAssoc.= "secret CHAR(255) NOT NULL,";
            $sSqlAssoc.= "issued INTEGER NOT NULL,";
            $sSqlAssoc.= "lifetime INTEGER NOT NULL,";
            $sSqlAssoc.= "assoc_type VARCHAR(64) NOT NULL,";
            $sSqlAssoc.= "PRIMARY KEY (server_url(255), handle)";
            $sSqlAssoc.= ") ENGINE=InnoDB";
            $this->_oDB->execute($sSqlAssoc);
        }
    }

    /**
     * This method selects an association that matches the server URL and,
     * if specified, handle.
     *
     * @param string $sServerUrl the URL of the identity server
     * @param string $sHandle    handle of the association to remove
     *
     * @return array $aRes
     */
    protected function _getAssoc($sServerUrl, $sHandle)
    {
        $sSql  = "SELECT handle, secret, issued, lifetime, assoc_type FROM ".$this->_sAssociationsTable;
        $sSql .= " WHERE server_url = ".$this->_oDB->quote( $sServerUrl )." AND handle = ".$this->_oDB->quote( $sHandle );
        $aRes = $this->_oDB->execute($sSql);
        if ($aRes) {
            return $aRes;
        } else {
            return false;
        }
    }

    /**
     * This method selects an association that matches the server URL
     *
     * @param string $sServerUrl the URL of the identity server
     *
     * @return array $aRes
     */
    protected function _getAssocs($sServerUrl)
    {
        $sSql  = "SELECT handle, secret, issued, lifetime, assoc_type FROM ".$this->_sAssociationsTable;
        $sSql .= " WHERE server_url = ".$this->_oDB->quote( $sServerUrl );
        $aRes = $this->_oDB->execute($sSql);
        if ($aRes) {
            return $aRes;
        } else {
            return false;
        }
    }

    /**
     * Encodes secret parameter for storing
     *
     * @param string $sSecret secret parameter
     *
     * @return string
     */
    public function blobEncode($sSecret)
    {
        return base64_encode($sSecret);
    }

    /**
     * Decodes secret parameter for storing
     *
     * @param string $sSecret secret parameter
     *
     * @return string
     */
    public function blobDecode($sSecret)
    {
        return base64_decode($sSecret);
    }

}
