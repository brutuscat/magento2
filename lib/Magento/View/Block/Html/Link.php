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
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\View\Block\Html;

/**
 * HTML anchor element block
 */
class Link extends \Magento\View\Block\Template
{
    /**
     * Prepare link attributes as serialized and formatted string
     *
     * @return string
     */
    public function getLinkAttributes()
    {
        $allow = array(
            'href', 'title', 'charset', 'name', 'hreflang', 'rel', 'rev', 'accesskey', 'shape',
            'coords', 'tabindex', 'onfocus', 'onblur', // %attrs
            'id', 'class', 'style', // %coreattrs
            'lang', 'dir', // %i18n
            'onclick', 'ondblclick', 'onmousedown', 'onmouseup', 'onmouseover', 'onmousemove',
            'onmouseout', 'onkeypress', 'onkeydown', 'onkeyup' // %events
        );

        $attributes = array();
        foreach ($allow as $attribute) {
            $value = $this->getDataUsingMethod($attribute);
            if (!is_null($value)) {
                $attributes[$attribute] = $this->escapeHtml($value);
            }
        }

        if (!empty($attributes)) {
            return $this->serialize($attributes);
        }

        return '';
    }

    /**
     * serialize attributes
     *
     * @param   array $attributes
     * @param   string $valueSeparator
     * @param   string $fieldSeparator
     * @param   string $quote
     * @return  string
     */
    public function serialize($attributes = array(), $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $data = array();
        foreach ($attributes as $key => $value) {
            $data[] = $key . $valueSeparator . $quote . $value . $quote;
        }

        return implode($fieldSeparator, $data);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }

        return '<a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getAnchorText()) . '</a>';
    }
}
