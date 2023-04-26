<?php

namespace Wagento\LoginAsCustomer\Model;

use Magento\Customer\Model\ResourceModel\Customer as ResourceCustomer;
use Magento\Customer\Model\CustomerFactory as ModelCustomerFactory;
use Magento\Customer\Model\Customer as ModelCustomer;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\LoginAsCustomerApi\Api\Data\AuthenticationDataInterface;
use Magento\LoginAsCustomerApi\Api\Data\AuthenticationDataInterfaceFactory;
use Magento\LoginAsCustomerApi\Api\GetAuthenticationDataBySecretInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class GetAuthenticationDataBySecret
 */
class GetAuthenticationDataBySecret implements GetAuthenticationDataBySecretInterface
{
    /**#@+
     * Constants
     */
    private const CUSTOMER_ID = 'customer_id';
    private const ADMIN_ID = 'admin_id';
    private const TIME_STAMP = 'time_stamp';

    /**
     * Duration in seconds the encrypted data is valid for
     */
    private const DATA_LIFETIME = 35;

    /**
     * @var DateTime
     */
    private DateTime $dateTime;

    /**
     * @var EncryptorInterface
     */
    private EncryptorInterface $encryptor;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var AuthenticationDataInterfaceFactory
     */
    private AuthenticationDataInterfaceFactory $authenticationDataFactory;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var ModelCustomerFactory
     */
    protected ModelCustomerFactory $modelCustomerFactory;

    /**
     * @var ResourceCustomer
     */
    protected ResourceCustomer $customerResource;

    /**
     * @param DateTime                           $dateTime
     * @param EncryptorInterface                 $encryptor
     * @param SerializerInterface                $serializer
     * @param AuthenticationDataInterfaceFactory $authenticationDataFactory
     * @param StoreManagerInterface              $storeManager
     * @param ModelCustomerFactory               $modelCustomerFactory
     * @param ResourceCustomer                   $customerResource
     */
    public function __construct(
        DateTime $dateTime,
        EncryptorInterface $encryptor,
        SerializerInterface $serializer,
        AuthenticationDataInterfaceFactory $authenticationDataFactory,
        StoreManagerInterface $storeManager,
        ModelCustomerFactory $modelCustomerFactory,
        ResourceCustomer $customerResource
    ) {
        $this->dateTime = $dateTime;
        $this->encryptor = $encryptor;
        $this->serializer = $serializer;
        $this->authenticationDataFactory = $authenticationDataFactory;
        $this->storeManager = $storeManager;
        $this->modelCustomerFactory = $modelCustomerFactory;
        $this->customerResource = $customerResource;
    }

    /**
     * @inheritdoc
     */
    public function execute(string $secret): AuthenticationDataInterface
    {
        $data = $this->serializer->unserialize($this->encryptor->decrypt($secret));
        $currentTimestamp = $this->dateTime->timestamp();
        $authenticationDataLifeTime = $currentTimestamp - $data[self::TIME_STAMP];
        if (isset($data[self::ADMIN_ID])
            && isset($data[self::CUSTOMER_ID])
            && isset($data[self::TIME_STAMP])
            && $authenticationDataLifeTime < self::DATA_LIFETIME) {
            $customer = $this->getCustomer($data[self::CUSTOMER_ID]);
            if ($customer->getStoreId() == 0) {
                $storeId = $this->storeManager->getDefaultStoreView()->getId();
                $customer->setStoreId($storeId);
                $this->customerResource->save($customer);
            }
            return $this->authenticationDataFactory->create(
                [
                    'customerId' => $data[self::CUSTOMER_ID],
                    'adminId' => $data[self::ADMIN_ID],
                ]
            );
        } else {
            throw new LocalizedException(__('Fail to get authentication data.'));
        }
    }

    /**
     * @param $customerId
     *
     * @return ModelCustomer
     */
    public function getCustomer($customerId): ModelCustomer
    {
        $model = $this->modelCustomerFactory->create();
        $this->customerResource->load($model, $customerId);
        return $model;
    }
}
