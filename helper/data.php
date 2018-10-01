<?php
namespace Velaro\Chat\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_customerSession;
    protected $_request;
    protected $_recentlyViewed;
    protected $_checkoutSession;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Reports\Block\Product\Viewed $recentlyViewed,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->_customerSession = $customerSession;
        parent::__construct($context);
        $this->_request = $request;
        $this->_recentlyViewed = $recentlyViewed;
        $this->_checkoutSession = $checkoutSession;
    }

    public function getOrderId()
    {
        return $this->_checkoutSession->getLastOrderId();
    }

    public function getCustomerUrlBase($helper)
    {
        $route = 'velaro/redirecttocustomer/index';
        $params = [];
        return $helper->getUrl($route, $params);
    }

    public function getOrderUrl($helper)
    {
        $orderId = $this->_checkoutSession->getLastOrderId();
        if (is_null($orderId)) {
            return 'null';
        }
        $route = 'velaro/redirecttoorder/index';
        $params = [
            'orderid' => $orderId
        ];
        return $helper->getUrl($route, $params);
    }

    public function isCheckoutSuccessPage()
    {
        return $this->_request->getFullActionName() == 'checkout_onepage_success';
    }

    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
