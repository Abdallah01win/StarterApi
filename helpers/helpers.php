<?php

use App\Enums\RoleNames;
use Illuminate\Http\Request;

/**
 * Helper functions for request handling and pagination.
 */

/**
 * Check if the request has a 'list' parameter set to true.
 *
 * @throws \Illuminate\Validation\ValidationException If validation fails
 */
function _hasList(Request $request): bool
{
    $validated = $request->validate([
        'list' => 'string|in:true,false|sometimes',
    ]);

    return $validated['list'] ?? false;
}

/**
 * Gets the number of items per page for pagination.
 *
 * @param  int  $count  Optional total count of items
 * @return int Returns number of items per page
 */
function _paginatePages($count = 0): int
{
    $perPage = (int) (request()->get('per_page')) ?: 10;

    return $count > 0 && $perPage == -1 ? $count : $perPage;
}

/**
 * Get the role name string from a role ID
 *
 * @param  int  $role  The role ID to lookup
 * @return string The corresponding role name
 *
 * @throws Exception invalid role ID
 */
function _getRoleName(int $role): string
{
    try {
        return RoleNames::getValues()[$role];
    } catch (Exception $e) {
        throw new \InvalidArgumentException('Invalid role ID provided');
    }
}
