<?php
/**
 * Copyright © Wagento, Inc. All rights reserved.
 */
namespace Wagento\SocialMedia\Plugin\Model\Source;

use Magento\Variable\Model\Source\Variables;

/**
 * Class VariablePlugin
 */
class VariablePlugin
{
    /**
     * @param Variables $subject
     * @param $result
     * @return array
     */
    public function afterGetData(Variables $subject, $result)
    {
        $result[] = [
            'value' => 'wagento_social_media/links/facebook_link',
            'label' => __('Facebook Link'),
            'group_label' => 'Social Media'
        ];

        $result[] = [
            'value' => 'wagento_social_media/links/instagram_link',
            'label' => __('Instagram Link'),
            'group_label' => 'Social Media'
        ];

        $result[] = [
            'value' => 'wagento_social_media/links/twitter_link',
            'label' => __('X(Twitter) Link'),
            'group_label' => 'Social Media'
        ];

        return $result;
    }
}
