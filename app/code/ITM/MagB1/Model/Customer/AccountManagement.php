<?php

namespace ITM\MagB1\Model\Customer;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use ITM\MagB1\Api\Customer\AccountManagementInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Stdlib\StringUtils as StringHelper;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
use Magento\Framework\Exception\InputException;
use phpDocumentor\Parser\Exception;

class AccountManagement extends \Magento\Customer\Model\AccountManagement implements AccountManagementInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;


    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;


    /**
     * @var StringHelper
     */
    protected $stringHelper;


    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Encryptor
     */
    private $encryptor;


    /**
     * @var \Magento\Customer\Model\AuthenticationInterface
     */
    protected $authentication;

    /**
     * @var \Magento\Customer\Model\CustomerRegistry
     */
    private $customerRegistry;


    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param StringHelper $stringHelper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Customer\Model\CustomerRegistry $customerRegistry
     * @param Encryptor $encryptor
     * @param \Magento\Framework\Session\SessionManagerInterface|null $sessionManager
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        StringHelper $stringHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        Encryptor $encryptor,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager = null,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->_objectManager = $objectManager;

        $this->sessionManager = $sessionManager
            ?: ObjectManager::getInstance()->get(SessionManagerInterface::class);
            $this->stringHelper = $stringHelper;
        $this->scopeConfig = $scopeConfig;
        $this->customerRegistry = $customerRegistry;
        $this->encryptor = $encryptor;
        $this->resource = $resource;
    }

    /**
     * Retrieve minimum password length
     *
     * @return int
     */
    protected function getMinPasswordLength()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_MINIMUM_PASSWORD_LENGTH);
    }

    /**
     * Check password for presence of required character sets
     *
     * @param string $password
     * @return int
     */
    protected function makeRequiredCharactersCheck($password)
    {
        $counter = 0;
        $requiredNumber = $this->scopeConfig->getValue(self::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER);
        $return = 0;

        if (preg_match('/[0-9]+/', $password)) {
            $counter++;
        }
        if (preg_match('/[A-Z]+/', $password)) {
            $counter++;
        }
        if (preg_match('/[a-z]+/', $password)) {
            $counter++;
        }
        if (preg_match('/[^a-zA-Z0-9]+/', $password)) {
            $counter++;
        }

        if ($counter < $requiredNumber) {
            $return = $requiredNumber;
        }

        return $return;
    }

    /**
     * Create a hash for the given password
     *
     * @param string $password
     * @return string
     */
    protected function createPasswordHash($password)
    {
        return $this->encryptor->getHash($password, true);
    }

    /**
     * Get authentication
     *
     * @return AuthenticationInterface
     */
    private function getAuthentication()
    {
        if (!($this->authentication instanceof AuthenticationInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Customer\Model\AuthenticationInterface::class
            );
        } else {
            return $this->authentication;
        }
    }


    /**
     * {@inheritdoc}
     */
    public function changePassword($email, $newPassword, $websiteId = null)
    {

        if ($websiteId === null) {
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
        }

        try {
            // load customer by email
            $customer = $this->customerRepository->get($email, $websiteId);
            $this->checkPasswordDifferentFromEmail(
                $email,
                $newPassword
            );
            $this->checkPasswordStrength($newPassword);
            //Update secure data
            $customerSecure = $this->customerRegistry->retrieveSecureData($customer->getId());
            $customerSecure->setRpToken(null);
            $customerSecure->setRpTokenCreatedAt(null);
            $customerSecure->setPasswordHash($this->createPasswordHash($newPassword));
            $this->getAuthentication()->unlock($customer->getId());
            $this->sessionManager->destroy();
            $this->customerRepository->save($customer);
            return true;
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\InputException(__($e->getMessage()));
        }


        return false;
    }
    /**
     * {@inheritdoc}
     */
    public function deleteCustomerCompany($customerId)
    {

        $return_result = $this->_objectManager->create("\ITM\MagB1\Model\ReturnResult");
        $companyManagement = $this->_objectManager->get('Magento\Company\Api\CompanyManagementInterface');
        $companyRepository = $this->_objectManager->get('Magento\Company\Api\CompanyRepositoryInterface');

        $company = $companyManagement->getByCustomerId($customerId);
        if(!$company) {
            $return_result->setError(true);
            $return_result->setData("The customer does not have any company ");
            return  $return_result;
        }
        $companyId = $company->getId();
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $company_advanced_customer_entity = $this->resource->getTableName('company_advanced_customer_entity');
        $select_query = $connection->select()->from([
            'entity' => $company_advanced_customer_entity
        ], [
            'customer_id'
        ]);
        $bind[':company_id'] = $companyId;
        $select_query->where('entity.company_id = :company_id');
        $result = $connection->fetchAll($select_query, $bind);




        if(count($result) == 0) {
            $return_result->setError(true);
            $return_result->setData("No users assigned to this company");
        }
        else if(count($result) == 1) {
            try {
                $cId = $company->getId();
                $cName = $company->getCompanyName();
                $companyRepository->deleteById($companyId);

                $return_result->setError(false);
                $return_result->setData("Company with id '{$cId}' and name '{$cName}' has been deleted");
            }catch (\Exception $e) {
                $return_result->setError(true);
                $return_result->setData($e->getMessage());
            }
        }else {
            $return_result->setError(true);
            $return_result->setData("There are other users assigned to this company");

        }
        return $return_result;
    }
    /**
     * Check that password is different from email.
     *
     * @param string $email
     * @param string $password
     * @return void
     * @throws InputException
     */
    public function checkPasswordDifferentFromEmail($email, $password)
    {
        if (strcasecmp($password, $email) == 0) {
            throw new \Magento\Framework\Exception\InputException(__('Password cannot be the same as email address.'));
        }
    }
}

