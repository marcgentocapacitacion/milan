<?php

namespace Wagento\Affiliate\Block\Home;

use Magento\Framework\View\Element\Template\Context;
use Wagento\Affiliate\Model\ResourceModel\Affiliate\CollectionFactory;
use Wagento\Affiliate\Model\ResourceModel\Affiliate\Collection;

/**
 * Class ListAffiliate
 */
class ListAffiliate extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'Wagento_Affiliate::home/list_affiliate.phtml';

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    /**
     * @var Collection|null
     */
    protected ?Collection $collection = null;

    /**
     * @param Context    $context
     * @param CollectionFactory $collectionFactory
     * @param array      $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return Collection
     */
    public function getAffiliate(): Collection
    {
        if (!$this->collection) {
            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('active', ['eq' => '1']);
            $this->collection = $collection;
        }
        return $this->collection;
    }

    /**
     * @param string $image
     *
     * @return string
     */
    public function getImage(string $image): string
    {
        if (!$image) {
            return '';
        }
        $image = \json_decode($image, true);
        $image = $image[0] ?? false;
        if (!$image) {
            return '';
        }
        $path = $image['url'] ?? false;
        if (!$path) {
            return '';
        }
        return $this->getUrl() . $path;
    }
}
