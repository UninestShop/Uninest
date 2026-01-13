<?php

// This is a simple helper script to fix autoloading issues

echo "Starting fix for 'Class App\Models\Contact not found' error...\n";

echo "Clearing configuration cache...\n";
shell_exec('php artisan config:clear');

echo "Clearing application cache...\n";
shell_exec('php artisan cache:clear');

echo "Clearing route cache...\n";
shell_exec('php artisan route:clear');

echo "Clearing view cache...\n";
shell_exec('php artisan view:clear');

echo "Running composer dumpautoload...\n";
shell_exec('composer dumpautoload');

echo "✅ All done! Try your application now.\n";
echo "If you still encounter issues, please run:\n";
echo "php artisan optimize\n";
echo "php artisan migrate\n";

echo "\nIf the issue persists, check if the Contact.php file exists at:\n";
echo realpath(__DIR__) . "/app/Models/Contact.php\n";

if (file_exists(__DIR__ . '/app/Models/Contact.php')) {
    echo "✅ The Contact.php file exists.\n";
} else {
    echo "❌ The Contact.php file does NOT exist! Please check the path.\n";
}

echo "\nAlso check for any syntax errors in the Contact.php file.\n";
?>
