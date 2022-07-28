<?php
namespace ITM\MagB1\Api\Customer;

interface AccountManagementInterface
{

    /**
     * Send an email to the customer with a password reset link.
     *
     * @param string $email
     * @param string $newPassword
     * @param int $websiteId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function changePassword($email, $newPassword, $websiteId = null);

    /**
     * @param string $customerId
     * @return ITM\MagB1\Api\Data\ReturnResultDataInterface
     */
    public function deleteCustomerCompany($customerId);
}
