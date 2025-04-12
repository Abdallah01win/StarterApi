<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * HTTP Response Status Codes Enum
 *
 * @method static static SUCCESS() Returns 200 OK status code
 * @method static static CREATED() Returns 201 Created status code
 * @method static static ACCEPTED() Returns 202 Accepted status code
 * @method static static NO_CONTENT() Returns 204 No Content status code
 * @method static static UNAUTHORIZED() Returns 401 Unauthorized status code
 */
final class ResponseCode extends Enum
{
    const SUCCESS = 200;

    const CREATED = 201;

    const ACCEPTED = 202;

    const NO_CONTENT = 204;

    const UNAUTHORIZED = 401;
}
