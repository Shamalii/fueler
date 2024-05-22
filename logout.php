<?php
session_start();
session_destroy();

// Optional: Unset additional user data if needed

header('Location: login.html');
exit();
?>