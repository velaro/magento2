<?php
      namespace Velaro\Chat\Controller\Adminhtml\RedirectToCustomer;

class Index extends \Magento\Backend\App\Action
{
  /**
   * @var \Magento\Framework\View\Result\PageFactory
   */
    protected $request;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\Http $request
    ) {
         parent::__construct($context);
         $this->request = $request;
    }

    protected $_publicActions = ['index'];

  /**
   * redirect to customer edit
   *
   * @return \Magento\Framework\View\Result\Page
   */
    public function execute()
    {
        $customerId = $this->request->getParam('customerid');
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('customer/index/edit/id/' . $customerId);
        return $resultRedirect;
    }
}
