<?php
/**
 * This file is part of payjp4
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 *  https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\payjp4;

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
                    'config' => [
                        'name' => 'plguin.payjp.admin.config.sub_title',
                        'url' => 'payjp_admin_config'
                    ],
                    'plan' => [
                        'name' => '定期購入プラン',
                        'url' => 'admin_payjp_plan'
                    ]
                ]
            ]
        ];
    }
}
