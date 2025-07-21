<?php
/*
 * @Author: Warley Elias
 * @Email: warley.elias@pentagrama.com.br
 * @Date: 2025-07-21 15:24:11
 * @Last Modified by: Warley Elias
 * @Last Modified time: 2025-07-21 15:27:01
 */
 
namespace PHPCuong\CustomerLogin\Observer;

use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use PHPCuong\CustomerLogin\Model\Customer\Attribute\Source\Reason;

class LoginBefore implements ObserverInterface {
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


    public function execute(Observer $observer)
    {
        $email = $observer->getData('email');
        if (empty($email)) {
            return $this;
        }
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