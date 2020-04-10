<?php


namespace Plugin\PayJP\Entity;


use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Master\AbstractMasterEntity;

/**
 * Class PaymentStatus
 * @package Plugin\PayJP\Entity
 *
 * @ORM\Table(name="plg_pay_jp_payment_status")
 * @ORM\Entity(repositoryClass="Plug\PayJP\Repository\PaymentStatusRepository")
 */
class PaymentStatus extends AbstractMasterEntity
{
    /**
     * 未決済
     */
    const OUTSTANDING = 1;

    /**
     * 有効性チェック済み
     */
    const ENABLED = 2;

    /**
     * 仮売上
     */
    const PROVISIONAL_SALES = 3;

    /**
     * 実売上
     */
    const ACTUAL_SALES = 4;

    /**
     * キャンセル
     */
    const CANCEL = 5;
}