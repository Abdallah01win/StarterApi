<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @extends Enum<int>
 *
 * @method static static GENERAL()
 * @method static static ALERT()
 */
final class NotificationTypes extends Enum
{
    const GENERAL = 0;

    const ALERT = 1;
}
