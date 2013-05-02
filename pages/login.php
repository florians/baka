<?php  
 /**
 * @Author Florian Stettler, Adrian Locher
 * @Version 9
 * Create Date:   19.03.2013  creation of the file
 * 
 * This is the Login page you can log in here
 */
?>
<h1>Login</h1>
<form method="post">
  <input type="hidden" value="login" name="event"/>
  <table>
    <tr>
      <td class="label">Username</td>
      <td>
      <input type="text" name="username" value="<?= post('username') ?>" />
      </td>
    </tr>
    <tr>
      <td class="label">Password</td>
      <td>
      <input type="password" name="password" value="" />
      </td>
    </tr>
    <tr>
      <td class="label"></td>
      <td>
      <input type="submit" value=" Send " />
      </td>
    </tr>
  </table>
</form>
