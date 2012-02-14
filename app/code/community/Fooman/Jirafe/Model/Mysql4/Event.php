<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @package     Fooman_Jirafe
 * @copyright   Copyright (c) 2010 Jirafe Inc (http://www.jirafe.com)
 * @copyright   Copyright (c) 2010 Fooman Limited (http://www.fooman.co.nz)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Fooman_Jirafe_Model_Mysql4_Event extends Mage_Core_Model_Mysql4_Abstract
{

    protected function _construct ()
    {
        $this->_init('foomanjirafe/event', 'id');
    }

    public function getLastVersionNumber($siteId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('foomanjirafe/event'), array(new Zend_Db_Expr('max(version) as maxId')))
            ->where('site_id = ?', $siteId);

        $res = $this->_getReadAdapter()->fetchRow($select);
        return isset($res['maxId']) ? $res['maxId'] : 0;
    }

}