<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ModifyRequestStatus extends Enum
{
    const NO_APPROVE = 1;
    const APPROVE    = 2;
    const REFUSE     = 3;

    // 1.未承認
    // 2 承認
    // 3 拒否"
}
