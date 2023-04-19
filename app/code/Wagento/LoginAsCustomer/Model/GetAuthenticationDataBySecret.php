<?php

namespace Wagento\LoginAsCustomer\Model;

use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\LoginAsCustomerApi\Api\Data\AuthenticationDataInterface;
use Magento\LoginAsCustomerApi\Api\Data\AuthenticationDataInterfaceFactory;
use Magento\LoginAsCustomerApi\Api\GetAuthenticationDataBySecretInterface;

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
     * @param DateTime $dateTime
     * @param EncryptorInterface $encryptor
     * @param SerializerInterface $serializer
     * @param AuthenticationDataInterfaceFactory $authenticationDataFactory
     */
    public function __construct(
        DateTime $dateTime,
        EncryptorInterface $encryptor,
        SerializerInterface $serializer,
        AuthenticationDataInterfaceFactory $authenticationDataFactory
    ) {
        $this->dateTime = $dateTime;
        $this->encryptor = $encryptor;
        $this->serializer = $serializer;
        $this->authenticationDataFactory = $authenticationDataFactory;
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
}
