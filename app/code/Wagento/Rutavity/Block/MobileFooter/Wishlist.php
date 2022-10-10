<?php
/**
 * Rutavity homepage mobile footer wishlist block
 * @package Wagento_Rutavity
 * @author Rudie Wang <rudi.wang@wagento.com>
 */

namespace Wagento\Rutavity\Block\MobileFooter;

use Magento\Framework\View\Element\Template\Context;
use Magento\Wishlist\Model\WishlistFactory;
use Magento\Customer\Model\Session as CustomerSession;

class Wishlist extends \Magento\Framework\View\Element\Template
{

    /**
     * @var WishlistFactory
     */
    protected $wishlistFactory;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @param Context $context
     * @param WishlistFactory $wishlistFactory
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Context $context,
        WishlistFactory $wishlistFactory,
        CustomerSession $customerSession
    ) {
        $this->wishlistFactory = $wishlistFactory;
        $this->customerSession = $customerSession;

        parent::__construct($context);
    }

    /**
     * @return int
     */
    public function getWishlistCount() {
        $count = 0;

        if ($customerId = $this->customerSession->getCustomerId()) {
            $wishlist = $this->wishlistFactory->create()
                ->loadByCustomerId($customerId);

            if ($wishlist) {
                $count = $wishlist->getItemsCount();
            }
        }

        return $count;
    }
}
