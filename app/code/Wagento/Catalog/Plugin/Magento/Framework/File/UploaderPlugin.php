<?php
namespace Wagento\Catalog\Plugin\Magento\Framework\File;

use Magento\Framework\App\Action\Context;

class UploaderPlugin
{
    public function aroundSetAllowedExtensions(\Magento\Framework\File\Uploader $subject, \Closure $proceed, $extensions = [])
    {
        if (!in_array('svg', $extensions)) {
            $extensions[] = 'svg';
        }
        return $proceed($extensions);
    }

}
