<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_SalesRule
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Rule report resource model
 *
 * @category    Magento
 * @package     Magento_SalesRule
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\SalesRule\Model\Resource\Report;

class Rule extends \Magento\Reports\Model\Resource\Report\AbstractReport
{
    /**
     * @var \Magento\SalesRule\Model\Resource\Report\Rule\CreatedatFactory
     */
    protected $_createdatFactory;

    /**
     * @var \Magento\SalesRule\Model\Resource\Report\Rule\UpdatedatFactory
     */
    protected $_updatedatFactory;

    /**
     * @param \Magento\Logger $logger
     * @param \Magento\App\Resource $resource
     * @param \Magento\Core\Model\LocaleInterface $locale
     * @param \Magento\Reports\Model\FlagFactory $reportsFlagFactory
     * @param \Magento\Stdlib\DateTime $dateTime
     * @param Rule\CreatedatFactory $createdatFactory
     * @param Rule\UpdatedatFactory $updatedatFactory
     */
    public function __construct(
        \Magento\Logger $logger,
        \Magento\App\Resource $resource,
        \Magento\Core\Model\LocaleInterface $locale,
        \Magento\Reports\Model\FlagFactory $reportsFlagFactory,
        \Magento\Stdlib\DateTime $dateTime,
        \Magento\SalesRule\Model\Resource\Report\Rule\CreatedatFactory $createdatFactory,
        \Magento\SalesRule\Model\Resource\Report\Rule\UpdatedatFactory $updatedatFactory
    ) {
        parent::__construct($logger, $resource, $locale, $reportsFlagFactory, $dateTime);
        $this->_createdatFactory = $createdatFactory;
        $this->_updatedatFactory = $updatedatFactory;
    }

    /**
     * Resource Report Rule constructor
     *
     */
    protected function _construct()
    {
        $this->_setResource('salesrule');
    }

    /**
     * Aggregate Coupons data
     *
     * @param mixed $from
     * @param mixed $to
     * @return \Magento\SalesRule\Model\Resource\Report\Rule
     */
    public function aggregate($from = null, $to = null)
    {
        $this->_createdatFactory->create()->aggregate($from, $to);
        $this->_updatedatFactory->create()->aggregate($from, $to);
        $this->_setFlagData(\Magento\Reports\Model\Flag::REPORT_COUPONS_FLAG_CODE);

        return $this;
    }

    /**
     * Get all unique Rule Names from aggregated coupons usage data
     *
     * @return array
     */
    public function getUniqRulesNamesList()
    {
        $adapter = $this->_getReadAdapter();
        $tableName = $this->getTable('coupon_aggregated');
        $select = $adapter->select()
            ->from(
                $tableName,
                new \Zend_Db_Expr('DISTINCT rule_name')
            )
            ->where('rule_name IS NOT NULL')
            ->where('rule_name <> ?', '')
            ->order('rule_name ASC');

        $rulesNames = $adapter->fetchAll($select);

        $result = array();

        foreach ($rulesNames as $row) {
            $result[] = $row['rule_name'];
        }

        return $result;
    }
}
