<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class WorkRequired extends Enum implements LocalizedEnum
{
    const REQUIRED_YES = 'y';
    const REQUIRED_NO = 'n';
}
