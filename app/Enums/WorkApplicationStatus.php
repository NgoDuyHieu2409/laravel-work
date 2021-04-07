<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class WorkApplicationStatus extends Enum implements LocalizedEnum
{
    const APPLYING = 1; // 1 Đăng ký
    const ASSIGNED = 2; // 2. Đã xác nhận
    const CANCELED = 3; // 3. Đã hủy
    const FINISH   = 4; // 4, Kết thúc công việc
}
