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
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\GroupedProduct\Block\Cart\Item\Renderer;

use Magento\Catalog\Model\Config\Source\Product\Thumbnail as ThumbnailSource;

/**
 * Shopping cart item render block
 */
class Grouped extends \Magento\Checkout\Block\Cart\Item\Renderer
{
    /**
     * Path in config to the setting which defines if parent or child product should be used to generate a thumbnail.
     */
    const CONFIG_THUMBNAIL_SOURCE = 'checkout/cart/grouped_product_image';

    /**
     * @param \Magento\View\Element\Template\Context $context
     * @param \Magento\Catalog\Helper\Product\Configuration $productConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Core\Helper\Url $urlHelper
     * @param \Magento\Message\ManagerInterface $messageManager
     * @param array $data
     */
    public function __construct(
        \Magento\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Product\Configuration $productConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Core\Helper\Url $urlHelper,
        \Magento\Message\ManagerInterface $messageManager,
        array $data = array()
    ) {
        parent::__construct(
            $context,
            $productConfig,
            $checkoutSession,
            $imageHelper,
            $urlHelper,
            $messageManager,
            $data
        );
        $this->_isScopePrivate = true;
    }

    /**
     * Get item grouped product
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getGroupedProduct()
    {
        $option = $this->getItem()->getOptionByCode('product_type');
        if ($option) {
            return $option->getProduct();
        }
        return $this->getProduct();
    }

    /**
     * {@inheritdoc}
     */
    public function getProductForThumbnail()
    {
        /**
         * Show grouped product thumbnail if it must be always shown according to the related setting in system config
         * or if child product thumbnail is not available
         */
        if ($this->_storeConfig->getConfig(self::CONFIG_THUMBNAIL_SOURCE) == ThumbnailSource::OPTION_USE_PARENT_IMAGE
            || !($this->getProduct()->getThumbnail() && $this->getProduct()->getThumbnail() != 'no_selection')
        ) {
            $product = $this->getGroupedProduct();
        } else {
            $product = $this->getProduct();
        }
        return $product;
    }
}
