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

    public function getMostRecentlyViewed($imageHelper)
    {
        $items = $this->_recentlyViewed->getItemsCollection();
        $result = [];
        foreach ($items as $item) {
            $product = [];
            $product['name'] = $item->getName();
            $product['thumbnail'] = $imageHelper->init($item, 'product_page_image_small')->setImageFile($item->getFile())->resize(100, 100)->getUrl();
            $product['url'] = $item->getProductUrl();
            array_push($result, $product);
        }
        return json_encode($result);
    }

    public function getCustomerId()
    {
        $customerId = $this->_customerSession->getId();
        if (is_null($customerId)) {
            return 'null';
        }

        return $customerId;
    }

    public function getOrderId()
    {
        return $this->_checkoutSession->getLastOrderId();
    }

    public function getCustomerUrl($helper)
    {
        $customerId = $this->_customerSession->getId();
        if (is_null($customerId)) {
            return null;
        }
        $route = 'velaro/redirecttocustomer/index';
        $params = [
            'customerid' => $customerId
        ];
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

    public function getCartTotal($cart)
    {
        if ($cart->getItemsCount() == 0) {
            return 0;
        }
        $quote = $cart->getQuote();
        return $quote->getGrandTotal();
    }

    public function getAllCartItems($cart, $imageHelper)
    {
        if ($cart->getItemsCount() == 0) {
            return '[]';
        }
        $quote = $cart->getQuote();
        $items = $quote->getAllVisibleItems();
        $result = [];
        foreach ($items as $item) {
            $itemProduct = $item->getProduct();
            $product = [];
            $product['name'] = $itemProduct->getName();
            $product['price'] = $itemProduct->getPrice();
            $product['quantity'] = $item->getQty();
            $product['thumbnail'] = $imageHelper->init($itemProduct, 'product_page_image_small')->setImageFile($itemProduct->getFile())->resize(100, 100)->getUrl();
            $product['url'] = $itemProduct->getProductUrl();
            array_push($result, $product);
        }
        return json_encode($result);
    }
}
