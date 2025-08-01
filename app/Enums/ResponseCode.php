<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * HTTP Response Status Codes Enum
 *
 *  @extends Enum<int>
 *
 * @method static static SUCCESS() Returns 200 OK status code
 * @method static static CREATED() Returns 201 Created status code
 * @method static static ACCEPTED() Returns 202 Accepted status code
 * @method static static NO_CONTENT() Returns 204 No Content status code
 * @method static static UNAUTHORIZED() Returns 401 Unauthorized status code
 * @method static static NOT_FOUND() Returns 404 Not Found status code
 */
final class ResponseCode extends Enum
{
    const SUCCESS = 200;

    const CREATED = 201;

    const ACCEPTED = 202;

    const NO_CONTENT = 204;

    const UNAUTHORIZED = 401;

    const NOT_FOUND = 404;

    const UNPROCESSABLE_CONTENT = 422;

    const TOO_MANY_REQUESTS = 429;
}
