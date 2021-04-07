<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class WorkConfig extends Enum
{
    const WORKER = 'ứng viên';
    const CURRENCY = 'VNĐ';
}
