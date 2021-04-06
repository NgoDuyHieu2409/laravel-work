<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class MFKessaiStatus extends Enum implements LocalizedEnum
{
    const ENEXAMINED = 1;
    const PASSED     = 2;
    const REJECTED   = 3;

    // 1:審査中
    // 2:審査通過
    // 3:審査否決
}
