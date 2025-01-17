<?php

// Export all messages from multiple files into one
$messages = array_merge(
    // require 'miscellaneous.php',
    require resource_path('lang/en/miscellaneous.php'),
    require resource_path('lang/en/alerts_and_notifications.php'),
    require resource_path('lang/en/authentication_and_account_management.php'),
    require resource_path('lang/en/buttons_and_tabs.php'),
    require resource_path('lang/en/confirmation.php'),
    require resource_path('lang/en/error_and_maintenance.php'),
    require resource_path('lang/en/field_specific.php'),
    require resource_path('lang/en/instructions.php'),
    require resource_path('lang/en/loading.php'),
    require resource_path('lang/en/navigation_and_interface.php'),
    require resource_path('lang/en/success.php'),
    require resource_path('lang/en/validation.php')
);

return $messages;
