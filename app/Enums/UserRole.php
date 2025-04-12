<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SUPERADMIN()
 * @method static static ADMIN()
 * @method static static USER()
 */
final class UserRole extends Enum
{
    const SUPERADMIN = 0;
    const ADMIN = 1;
    const USER = 2;
}
