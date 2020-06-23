<?php

namespace Frontend\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Message extends Enum
{
    const processError = '情報処理の中に、エーラが発生しました。';
    const incorrectAccount = 'ユーザー情報が正しくない。';
}
