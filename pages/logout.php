<script>onlineCheck(0);</script>
<?php
$user = User::byId(session('id'));
$user -> setOnline('0');
$user -> save();
session_destroy();
header('Location:index.php?success=successfully logged out');
?>