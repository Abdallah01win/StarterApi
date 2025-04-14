<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @extends Enum<string>
 *
 * @method static static SUPER_ADMIN()
 * @method static static ADMIN()
 * @method static static USER()
 */
final class RoleNames extends Enum
{
    const SUPER_ADMIN = 'super-admin';

    const ADMIN = 'admin';

    const USER = 'user';
}
