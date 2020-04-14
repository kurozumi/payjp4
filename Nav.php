<?php

namespace Plugin\PayJP;

use Eccube\Common\EccubeNav;

class Nav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
        return [
            'payjp' => [
                'name' => 'PAY.JP',
                'icon' => 'fa-cube',
                'children' => [
                    'plan' => [
                        'name' => '定期購入プラン',
                        'url' => 'admin_payjp_plan'
                    ]
                ]
            ]
        ];
    }
}
