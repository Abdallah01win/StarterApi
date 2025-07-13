<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Accepted controller actions
 *
 *  @extends Enum<string>
 * */
final class ControllerActions extends Enum
{
    const STORE = 'store';

    const UPDATE = 'update';
}
