<?php

namespace Backend\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserRole extends Enum
{
    const Member = 1;
    const Contributor =   2;
    const Editor = 3;
    const Admin = 4;
}
