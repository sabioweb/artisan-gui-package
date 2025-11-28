<?php

declare(strict_types=1);

return [
    // Navigation
    'nav.dashboard' => 'Dashboard',
    'nav.run_command' => 'Run Command',
    'nav.catalog' => 'Commands Catalog',
    'nav.history' => 'History',
    'nav.about' => 'About',

    // Dashboard
    'dashboard.title' => 'Dashboard',
    'dashboard.subtitle' => 'Overall status of Artisan command executions',
    'dashboard.stats.total_runs' => 'Total Runs',
    'dashboard.stats.recent_successful' => 'Successful (24h)',
    'dashboard.stats.recent_failed' => 'Failed (24h)',
    'dashboard.stats.success_rate' => 'Success Rate',
    'dashboard.recent_runs' => 'Recent Runs',
    'dashboard.table.command' => 'Command',
    'dashboard.table.status' => 'Status',
    'dashboard.table.executor' => 'Executor',
    'dashboard.table.time' => 'Time',
    'dashboard.status.success' => 'Success',
    'dashboard.status.failed' => 'Failed',
    'dashboard.status.running' => 'Running',
    'dashboard.no_runs' => 'No executions found',
    'dashboard.unknown' => 'Unknown',

    // Run Command
    'run.title' => 'Run Artisan Command',
    'run.subtitle' => 'Select the desired command and enter its parameters',
    'run.form.command' => 'Command',
    'run.form.select_command' => '-- Select Command --',
    'run.form.parameters' => 'Parameters',
    'run.form.clear' => 'Clear',
    'run.form.execute' => 'Execute Command',
    'run.output.title' => 'Output',
    'run.output.executing' => 'Executing...',
    'run.output.success' => 'Command executed successfully',
    'run.output.failed' => 'Command execution failed',
    'run.output.server_error' => 'Server connection error: :error',
    'run.no_description' => 'No description',

    // Catalog
    'catalog.title' => 'Artisan Commands Catalog',
    'catalog.subtitle' => 'All allowed commands for execution',
    'catalog.search.placeholder' => 'Search command...',
    'catalog.no_results' => 'No commands found',
    'catalog.options' => 'Options',
    'catalog.more' => 'more',
    'catalog.execute' => 'Execute',
    'catalog.no_description' => 'No description',

    // History
    'history.title' => 'Command Execution History',
    'history.subtitle' => 'All previous Artisan command executions',
    'history.table.command' => 'Command',
    'history.table.status' => 'Status',
    'history.table.executor' => 'Executor',
    'history.table.execution_time' => 'Execution Time',
    'history.table.duration' => 'Duration',
    'history.table.actions' => 'Actions',
    'history.view' => 'View',
    'history.download_log' => 'Download Log',
    'history.no_runs' => 'No executions found',
    'history.details.title' => 'Execution Details',
    'history.details.command' => 'Command',
    'history.details.status' => 'Status',
    'history.details.time' => 'Time',
    'history.details.duration' => 'Duration',
    'history.details.output' => 'Output',
    'history.details.error' => 'Error',
    'history.details.no_output' => 'No output',
    'history.details.close' => 'Close',
    'history.details.fetch_error' => 'Error fetching details',
    'history.duration.seconds' => 'seconds',

    // About
    'about.title' => 'About Artisan GUI',
    'about.project.title' => 'Project Information',
    'about.project.name' => 'Project Name',
    'about.project.version' => 'Version',
    'about.author.title' => 'Author',
    'about.author.name' => 'Name',
    'about.author.email' => 'Email',
    'about.features.title' => 'Features',
    'about.features.list' => [
        'Secure execution of Artisan commands through web interface',
        'Whitelisted commands with customization capability',
        'Real-time output and log display',
        'Complete execution history with audit trail',
        'Permission management based on user roles',
        'Modular package installable via Composer',
    ],
    'about.license' => 'This package is released under the MIT license.',

    // Alerts
    'alert.success' => 'Success',
    'alert.error' => 'Error',
    'alert.confirm' => 'OK',
];

