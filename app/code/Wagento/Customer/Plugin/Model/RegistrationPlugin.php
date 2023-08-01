<?php
namespace Wagento\Customer\Plugin\Model;

use Magento\Customer\Model\Registration;
class RegistrationPlugin
{
    /**
     * @param Registration $subject
     * @param boolean $result
     * @return false
     */
    public function afterIsAllowed(Registration $subject, $result)
    {
        return false;
    }
}
