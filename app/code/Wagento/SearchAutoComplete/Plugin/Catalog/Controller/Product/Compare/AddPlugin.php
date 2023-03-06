<?php

namespace Wagento\SearchAutoComplete\Plugin\Catalog\Controller\Product\Compare;

use Laminas\Uri\Uri;
use Magento\Catalog\Controller\Product\Compare\Add;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;

/**
 * Class AddPlugin
 */
class AddPlugin
{
    /**
     * @var Uri
     */
    private Uri $uri;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var RedirectFactory
     */
    private RedirectFactory $resultRedirectFactory;

    /**
     * @var RedirectInterface
     */
    protected RedirectInterface $redirect;

    /**
     * @param Uri               $uri
     * @param RequestInterface  $request
     * @param RedirectFactory   $resultRedirectFactory
     * @param RedirectInterface $redirect
     */
    public function __construct(
        Uri $uri,
        RequestInterface $request,
        RedirectFactory $resultRedirectFactory,
        RedirectInterface $redirect
    ) {
        $this->uri = $uri;
        $this->request = $request;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->redirect = $redirect;
    }

    /**
     * @param Add $subject
     * @param Redirect $result
     *
     * @return Redirect
     */
    public function afterExecute(Add $subject, $result)
    {
        $redirectParsedUrl = $this->uri->parse($this->redirect->getRedirectUrl());
        if (str_contains($redirectParsedUrl->getPath(), 'searchautocomplete')) {
            /** @var Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setUrl($this->request->getServer('HTTP_REFERER'));
            return $resultRedirect;
        }
        return $result;
    }
}
