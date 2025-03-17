<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Accepted application locals
 *
 *  @extends Enum<string>
 *
 * @method static static EN() Returns 'en' local
 * @method static static FR() Returns 'fr' local
 * */
final class Locals extends Enum
{
    const EN = 'en';

    const FR = 'fr';
}
