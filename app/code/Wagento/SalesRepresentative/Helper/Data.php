<?php
/**
 * Sales representative helper
 * @package Wagento_SalesRepresentative
 * @author Rudie Wang <rudi.wang@wagento.com>
 */

namespace Wagento\SalesRepresentative\Helper;

use Magento\Backend\Model\Auth\Session;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Wagento\ColumbiaCities\Api\ColumbiaCitiesRepositoryInterface;

class Data extends AbstractHelper
{
    const SALES_REPRESENTATIVE_ROLE_NAME = 'RepresentanteCliente';

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var CompanyManagementInterface
     */
    protected $companyManagement;

    /**
     * @var CompanyRepositoryInterface
     */
    protected $companyRepository;

    /**
     * @var OrderInterface
     */
    protected $orderRepository;

    /**
     * @var ColumbiaCitiesRepositoryInterface
     */
    protected $authSession;

    /**
     * @param Context $context
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CompanyManagementInterface $companyManagement
     * @param CompanyRepositoryInterface $companyRepository
     * @param OrderInterface $orderRepository
     * @param Session $authSession
     */
    public function __construct(
        Context               $context,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CompanyManagementInterface $companyManagement,
        CompanyRepositoryInterface $companyRepository,
        OrderInterface        $orderRepository,
        Session               $authSession
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->companyManagement = $companyManagement;
        $this->companyRepository = $companyRepository;
        $this->orderRepository = $orderRepository;
        $this->authSession = $authSession;

        parent::__construct($context);
    }

    /**
     * Get sales representative by customer_id and compare with current admin user
     * @param $customerId
     * @return bool
     */
    public function isCustomerEditAllowed($customerId)
    {
        if ($this->isSalesRepresentative()) {
            $company = $this->companyManagement->getByCustomerId($customerId);
            if ( $company->getSalesRepresentativeId() != $this->getUserId() ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get sales representative by company_id and compare with current admin user
     * @param $companyId
     * @return bool
     */
    public function isCompanyEditAllowed($companyId)
    {
        if ($this->isSalesRepresentative()) {
            try {
                $company = $this->companyRepository->get($companyId);
                if ( $company->getSalesRepresentativeId() != $this->getUserId() ) {
                    return false;
                }
            } catch (NoSuchEntityException $e) {
            }
        }

        return true;
    }

    /**
     * Get sales representative by order_id and compare with current admin user
     * @param $orderId
     * @return bool
     */
    public function isOrderViewAllowed($orderId)
    {
        if ($this->isSalesRepresentative()) {
            try {
                $order = $this->orderRepository->load($orderId);
                $customerId = $order->getCustomerId();

                return $this->isCustomerEditAllowed($customerId);
            } catch (NoSuchEntityException $e) {
            }
        }

        return true;
    }

    /**
     * Check if current user is Sales representative user
     * @return bool
     */
    public function isSalesRepresentative()
    {
        $isSR = false;

        if ($user = $this->getCurrentUser()) {
            if ($user->getRole()->getRoleName() == self::SALES_REPRESENTATIVE_ROLE_NAME) {
                $isSR = true;
            }
        }

        return $isSR;
    }

    /**
     * Get current admin user name
     * @return string
     */
    public function getUserName()
    {
        $username = '';

        if ($user = $this->getCurrentUser()) {
            $username = (string) $user->getUserName();
        }

        return $username;
    }

    /**
     * Get current admin user id
     * @return int
     */
    public function getUserId()
    {
        $username = 0;

        if ($user = $this->getCurrentUser()) {
            $username = (int) $user->getId();
        }

        return $username;
    }

    /**
     * Get current admin user
     * @return \Magento\User\Model\User|null
     */
    public function getCurrentUser()
    {
        return $this->authSession->getUser();
    }
}
