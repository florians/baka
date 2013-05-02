<?php
/**
 * @Author Florian Stettler
 * @Version 2
 * Create Date:   19.03.2013  create of the file
 *                02.05.2013  html5 validation
 * 
 * This file contains the content of the Registration Page.
 * On the page is only the whole form for the registration.
 */
?>
<h1>Register</h1>
<form method="post">
  <input type="hidden" value="registration" name="event"/>
  <table>
    <tr>
      <td class="label">Firstname</td>
      <td>
      <input type="text" value="<?= decode(post('firstname')) ?>" name="firstname" required title="Firstname is Required!"/>
      </td>
    </tr>
    <tr>
      <td class="label">Lastname</td>
      <td>
      <input type="text" value="<?= decode(post('lastname')) ?>" name="lastname" required title="Lastname is Required!"/>
      </td>
    </tr>
    <tr>
      <td class="label">Username</td>
      <td>
      <input type="text" value="<?= decode(post('username')) ?>" name="username" required title="Username is Required!"/>
      </td>
    </tr>
    <tr>
      <td class="label">Password</td>
      <td>
      <input type="password" value="<?= decode(post('passworda')) ?>" name="passworda" required title="Password is Required!"/>
      </td>
    </tr>
    <tr>
      <td class="label">Retype</td>
      <td>
      <input type="password" value="<?= decode(post('passwordb')) ?>" name="passwordb" required title="Retype is Required!"/>
      </td>
    </tr>
    <tr>
      <td class="label">E-Mail</td>
      <td>
      <input type="email" value="<?= decode(post('email')) ?>" name="email" required title="E-Mail is Required!"/>
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