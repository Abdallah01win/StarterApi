<?php

use Illuminate\Http\Request;

function _hasList(Request $request): bool
{
    $validated = $request->validate([
        'list' => 'string|in:true,false|sometimes'
    ]);

    return $validated['list'] ?? false;
}

function _paginatePages($count = 0): int
{
    $perPage = (int) (request()->get('per_page')) ?: 10;

    return $count > 0 && $perPage == -1 ? $count : $perPage;
}
