<?php
// Simple tool to generate password hash
// DELETE THIS FILE after you've updated the password!

$password = 'admin123'; // The password you want to use

$hashed = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Password Hash Generator</h2>";
echo "<p><strong>Original Password:</strong> " . htmlspecialchars($password) . "</p>";
echo "<p><strong>Hashed Password:</strong></p>";
echo "<textarea style='width: 100%; height: 100px; font-family: monospace;'>" . $hashed . "</textarea>";
echo "<br><br>";
echo "<p>Copy the hashed password above and use it in Step 3 below.</p>";
?>