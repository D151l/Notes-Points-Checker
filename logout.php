<?php
session_start();
session_unset();
session_destroy();
echo '<script language="javascript" type="text/javascript"> document.location="index.php"; </script>';
?>