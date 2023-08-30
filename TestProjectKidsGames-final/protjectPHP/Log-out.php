
<?php
session_start();

// Destroy all session data
session_destroy();

// Show the alert
echo "<script>alert('You disconnected successfully');</script>";

// Redirect to the login page after a short delay (2 seconds in this example)
header("Refresh: 1; url=index.html");
exit;
?>