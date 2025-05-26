<?php

use Illuminate\Support\Facades\Schedule;

if (env('BACKUP_ENABLED', false)) {
    $backup_command = 'backup:run' . (env('BACKUP_APP_FILES', false) ? '' : ' --only-db');

    Schedule::command('backup:clean')->daily()->at('00:00')
        ->onFailure(function () {
            Log::error('Backup Cleanup failed: Scheduled cleanup task failed to execute');
        });

    Schedule::command($backup_command)->daily()->at('00:30')
        ->onFailure(function () {
            Log::error('Backup failed: Scheduled backup task failed to execute');
        });
}
