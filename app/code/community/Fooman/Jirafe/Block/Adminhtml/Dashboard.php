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

class Fooman_Jirafe_Block_Adminhtml_Dashboard extends Mage_Adminhtml_Block_Dashboard
{

    public function __construct ()
    {
        parent::__construct();
        $helper = Mage::helper('foomanjirafe/data');
        if ($helper->isDashboardActive() && $helper->isOk()) {
            $this->setTemplate('fooman/jirafe/dashboard.phtml');
        }
        $adminSession = Mage::getSingleton('admin/session');
        if (!$adminSession->getUser()->getJirafeOptinAnswered()) {
            Mage::getSingleton('adminhtml/session')->addNotice(
                $helper->__('Would you like access to advanced analytics from Jirafe? <a href="%s">Yes</a> <a href="%s">No</a>',
                    Mage::helper('adminhtml')->getUrl('adminhtml/jirafe/optin', array('_query' => array('answer' => 'yes'))),
                    Mage::helper('adminhtml')->getUrl('adminhtml/jirafe/optin', array('_query' => array('answer' => 'no')))
                )
            );
        }
    }

}
