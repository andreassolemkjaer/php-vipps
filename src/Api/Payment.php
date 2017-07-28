<?php

namespace Vipps\Api;

use Vipps\Exceptions\Api\InvalidArgumentException;
use Vipps\Model\Payment\CustomerInfo;
use Vipps\Model\Payment\MerchantInfo;
use Vipps\Model\Payment\RequestInitiatePayment;
use Vipps\Model\Payment\Transaction;
use Vipps\Resource\Payment\GetOrderStatus;
use Vipps\Resource\Payment\GetPaymentDetails;
use Vipps\Resource\Payment\InitiatePayment;
use Vipps\VippsInterface;

class Payment extends ApiBase implements PaymentInterface
{

    /**
     * @var string
     */
    protected $merchantSerialNumber;

    /**
     * Gets merchantSerialNumber value.
     *
     * @return string
     */
    public function getMerchantSerialNumber()
    {
        if (empty($this->merchantSerialNumber)) {
            throw new InvalidArgumentException('Missing merchant serial number');
        }
        return $this->merchantSerialNumber;
    }

    /**
     * Payment constructor.
     *
     * @param \Vipps\VippsInterface $app
     * @param string $subscription_key
     * @param $merchant_serial_number
     */
    public function __construct(VippsInterface $app, $subscription_key, $merchant_serial_number)
    {
        parent::__construct($app, $subscription_key);
        $this->merchantSerialNumber = $merchant_serial_number;
    }

    public function cancelPayment()
    {

    }

    public function capturePayment()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getOrderStatus($order_id)
    {
        $resource = new GetOrderStatus($this->app, $this->getSubscriptionKey(), $this->getMerchantSerialNumber(), $order_id);
        /** @var \Vipps\Model\Payment\ResponseGetOrderStatus $response */
        $response = parent::doRequest($resource);
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentDetails($order_id)
    {
        $resource = new GetPaymentDetails($this->app, $this->getSubscriptionKey(), $this->getMerchantSerialNumber(), $order_id);
        /** @var \Vipps\Model\Payment\ResponseGetPaymentDetails $response */
        $response = parent::doRequest($resource);
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function initiatePayment($order_id, $mobile_number, $amount, $text, $callback, $refOrderID = null)
    {
        $request = (new RequestInitiatePayment())
            ->setCustomerInfo(
                (new CustomerInfo())
                    ->setMobileNumber($mobile_number)
            )
            ->setMerchantInfo(
                (new MerchantInfo())
                    ->setCallBack($callback)
                    ->setMerchantSerialNumber($this->getMerchantSerialNumber())
            )
            ->setTransaction(
                (new Transaction())
                    ->setTransactionText($text)
                    ->setAmount($amount)
                    ->setOrderId($order_id)
                    ->setRefOrderId($refOrderID)
            );
        $resource = new InitiatePayment($this->app, $this->getSubscriptionKey(), $request);
        /** @var \Vipps\Model\Payment\ResponseInitiatePayment $response */
        $response = parent::doRequest($resource);
        return $response;
    }

    public function refundPayment()
    {

    }
}