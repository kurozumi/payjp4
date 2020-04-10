<?php

namespace Plugin\PayJP;

use Eccube\Common\EccubeTwigBlock;

class TwigBlock implements EccubeTwigBlock
{
    /**
     * @return array
     */
    public static function getTwigBlock()
    {
        return [
            '@PayJP/credit.twig'
        ];
    }
}
