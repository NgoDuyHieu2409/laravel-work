<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class WorkStatus extends Enum implements LocalizedEnum
{
    const RECRUITMENT_START = 1;
    const WANTED            = 2;
    const RECRUITMENT_END   = 3;
    const SELECTED          = 4;
    const WORKING           = 5;
    const BUSINESS          = 6;
    const REQUESTING        = 7;
    const FINISH            = 8;

    // 1:募集前
    // 2:募集中
    // 3:募集終了
    // 4:選定済
    // 5:業務中
    // 6:業務完了
    // 7:修正依頼中
    // 8:（報酬確定）（仕事完了）
}
