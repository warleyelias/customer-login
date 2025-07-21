<?php
/*
 * @Author: Warley Elias
 * @Email: warley.elias@pentagrama.com.br
 * @Date: 2025-07-21 13:23:40
 * @Last Modified by: Warley Elias
 * @Last Modified time: 2025-07-21 15:55:12
 */

namespace PHPCuong\CustomerLogin\Plugin\Customer\Model\AccountManagement;

use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Model\AccountManagement\Authenticate;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use PHPCuong\CustomerLogin\Model\Customer\Attribute\Source\Reason;

class AuthenticatePlugin {
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var Reason
     */
    protected $reasonsList;

    /**
     * @param CustomerRepository $customerRepository
     * @param Reason $reasonsList
     */
    public function __construct(
        CustomerRepository $customerRepository,
        Reason $reasonsList
    ) {
        $this->customerRepository = $customerRepository;
        $this->reasonsList = $reasonsList;
    }

    /**
     * After execute plugin for Authenticate
     *
     * @param Authenticate $subject
     * @param string $email
     * @param string $password
     */
    public function beforeExecute(Authenticate $subject, string $email, string $password) {
        try {
            $customer = $this->customerRepository->get($email);
        } catch (NoSuchEntityException $exception) {
            throw new InvalidEmailOrPasswordException(__('Invalid login or password.'));
        }

        $loginStatus = $customer->getCustomAttribute('login_status');
        if ($loginStatus && $loginStatus->getValue() == '1') {
            $reasonCode = $customer->getCustomAttribute('reason');
            $reasonValue = '';
            if (!empty($reasonCode)) {
                $reasonValue = $reasonCode->getValue();
            }
            $errorMessage = __('Your account is blocked for the following reason: %1', $this->reasonsList->getMessage($reasonValue));
            throw new LocalizedException($errorMessage);
        }
    }
}