<?php

namespace Wagento\Company\Block\Company\Register;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\DataObject;
use Magento\Company\Block\Company\Register\Profile as MagentoProfile;

/**
 * Class Profile
 */
class Profile extends MagentoProfile
{
    /**
     * @var \Magento\Company\Api\CompanyManagementInterface
     */
    private $companyManagement;

    /**
     * @var \Magento\Company\Model\CountryInformationProvider
     */
    private $countryInformationProvider;

    /**
     * @var \Magento\Customer\Api\CustomerMetadataInterface
     */
    protected $customerMetadata;

    /**
     * @var \Magento\Company\Model\Create\Session
     */
    private $companyCreateSession;

    /**
     * @var \Magento\Company\Api\Data\CompanyInterface
     */
    private $company;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterface
     */
    private $companyAdmin;

    /**
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param \Magento\Company\Api\CompanyManagementInterface   $companyManagement
     * @param \Magento\Company\Model\CountryInformationProvider $countryInformationProvider
     * @param \Magento\Customer\Api\CustomerMetadataInterface   $customerMetadata
     * @param \Magento\Company\Model\Create\Session             $companyCreateSession
     * @param array                                             $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Company\Api\CompanyManagementInterface $companyManagement,
        \Magento\Company\Model\CountryInformationProvider $countryInformationProvider,
        \Magento\Customer\Api\CustomerMetadataInterface $customerMetadata,
        \Magento\Company\Model\Create\Session $companyCreateSession,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $companyManagement,
            $countryInformationProvider,
            $customerMetadata,
            $companyCreateSession,
            $data
        );
        $this->companyManagement = $companyManagement;
        $this->countryInformationProvider = $countryInformationProvider;
        $this->customerMetadata = $customerMetadata;
        $this->companyCreateSession = $companyCreateSession;
    }

    /**
     * Get company information.
     *
     * @return DataObject[]
     */
    public function getCompanyInformation()
    {
        $companyInformation = parent::getCompanyInformation();
        $company = $this->getCompany();

        $companyInformation[] = $this->createField(
            __('Almacen:'),
            $company->getAlmacen()
        );

        return $companyInformation;
    }

    /**
     * Get admin information.
     *
     * @return DataObject[]
     */
    public function getAdminInformation()
    {
        $adminInformation = parent::getAdminInformation();
        $companyAdmin = $this->getCompanyAdmin();
        if (!$companyAdmin) {
            return $adminInformation;
        }

        if (!method_exists($companyAdmin, 'getAlmacen')) {
            return $adminInformation;
        }

        if (!$companyAdmin->getAlmacen()) {
            return $adminInformation;
        }

        $adminInformation[] = $this->createField(
            __('Almacen:'),
            $companyAdmin->getAlmacen()
        );
        return $adminInformation;
    }

    /**
     * Get company admin.
     *
     * @return CustomerInterface
     */
    private function getCompanyAdmin()
    {
        if ($this->companyAdmin === null) {
            $company = $this->getCompany();
            $this->companyAdmin = $this->companyManagement->getAdminByCompanyId($company->getId());
        }

        return $this->companyAdmin;
    }

    /**
     * Create field object.
     *
     * @param string|\Magento\Framework\Phrase $label
     * @param string $value
     * @return DataObject
     */
    private function createField($label, $value)
    {
        return new DataObject([
            'label' => $label,
            'value' => $value
        ]);
    }

    /**
     * Get company.
     *
     * @return CompanyInterface
     */
    private function getCompany()
    {
        if ($this->company !== null) {
            return $this->company;
        }

        $customerId = $this->companyCreateSession->getCustomerId();
        if ($customerId) {
            $this->company = $this->companyManagement->getByCustomerId($customerId);
        }

        return $this->company;
    }
}
