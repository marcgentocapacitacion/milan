<?php
/**
 * Add company & sales representative to order creation customer collection
 * @package Wagento_SalesRepresentative
 * @author Rudie Wang <rudi.wang@wagento.com>
 */
namespace Wagento\SalesRepresentative\Model\ResourceModel\Order\Customer;

use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\EntityFactory as EavEntityFactory;
use Magento\Eav\Model\ResourceModel\Helper;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DataObject;
use Magento\Framework\DataObject\Copy\Config;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot;
use Magento\Framework\Validator\UniversalFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Wagento\SalesRepresentative\Helper\Data as SalesRepresentativeHelper;

/**
 * Customer Grid Collection
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Collection extends \Magento\Sales\Model\ResourceModel\Order\Customer\Collection
{

    const SALES_REPRESENTATIVE_ATTRIBUTE_NAME = 'sales_representative.username';

    /**
     * @var SalesRepresentativeHelper
     */
    protected $salesRepresentativeHelper;

    /**
     * Constructor
     *
     * @param EntityFactory $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param EavConfig $eavConfig
     * @param ResourceConnection $resource
     * @param EavEntityFactory $eavEntityFactory
     * @param Helper $resourceHelper
     * @param UniversalFactory $universalFactory
     * @param Snapshot $entitySnapshot
     * @param Config $fieldsetConfig
     * @param StoreManagerInterface $storeManager
     * @param SalesRepresentativeHelper $salesRepresentativeHelper
     * @param AdapterInterface|null $connection
     * @param string $modelName
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        EntityFactory $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        EavConfig $eavConfig,
        ResourceConnection $resource,
        EavEntityFactory $eavEntityFactory,
        Helper $resourceHelper,
        UniversalFactory $universalFactory,
        Snapshot $entitySnapshot,
        Config $fieldsetConfig,
        StoreManagerInterface $storeManager,
        SalesRepresentativeHelper $salesRepresentativeHelper,
        AdapterInterface $connection = null,
        $modelName = self::CUSTOMER_MODEL_NAME
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $universalFactory,
            $entitySnapshot,
            $fieldsetConfig,
            $storeManager,
            $connection,
            $modelName
        );

        $this->salesRepresentativeHelper = $salesRepresentativeHelper;
        $this->_initSalesRepresentative();
    }

    /**
     * Init SalesRepresentative and filter by company's sales representative user
     *
     * @return $this
     */
    protected function _initSalesRepresentative()
    {
        try {
            if ($this->salesRepresentativeHelper->isSalesRepresentative()) {
                if ($paramValue = $this->salesRepresentativeHelper->getUserName()) {
                    // Join company sales representative data
                    $this->joinTable(
                        ['company_customer' => $this->getTable('company_advanced_customer_entity')],
                        'customer_id = entity_id',
                        [
                            'company_id' => 'company_id'
                        ],
                        null,
                        'left'
                    )->joinTable(
                        ['company' => $this->getTable('company')],
                        'entity_id = company_id',
                        [
                            'company_name' => 'company_name',
                            'sales_representative_id' => 'sales_representative_id',
                        ],
                        null,
                        'left'
                    )->joinTable(
                        ['sales_representative' => $this->getTable('admin_user')],
                        'user_id = sales_representative_id',
                        [
                            'sales_representative_username' => 'sales_representative.username'
                        ],
                        null,
                        'left'
                    );

                    // Filter by sales_representative user
                    $this->getSelect()->where(self::SALES_REPRESENTATIVE_ATTRIBUTE_NAME . " = '$paramValue'");
                }
            }
        } catch (LocalizedException $e) {
            $this->_logger->error($e->getMessage());
        }

        return $this;
    }

}
