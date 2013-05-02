<?php  
 /**
 * @Author Florian Stettler, Adrian Locher
 * @Version 9
 * Create Date:   19.03.2013  creation of the file
 * 
 * This is the logout page it imidiatly loges you out.
 */
?>
<script>onlineCheck(0);</script>
<?php
$user = User::byId(session('id'));
$user -> setOnline('0');
$user -> save();
session_destroy();
header('Location:index.php?success=successfully logged out');
?>