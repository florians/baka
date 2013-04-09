<h1>Register</h1>
<form method="post">
  <input type="hidden" value="registration" name="event"/>
  <table>
    <tr>
      <td class="label">Firstname</td>
      <td>
      <input type="text" value="<?= decode(post('firstname')) ?>" name="firstname" />
      </td>
    </tr>
    <tr>
      <td class="label">Lastname</td>
      <td>
      <input type="text" value="<?= decode(post('lastname')) ?>" name="lastname" />
      </td>
    </tr>
    <tr>
      <td class="label">Username</td>
      <td>
      <input type="text" value="<?= decode(post('username')) ?>" name="username" />
      </td>
    </tr>
    <tr>
      <td class="label">Password</td>
      <td>
      <input type="password" value="<?= decode(post('passworda')) ?>" name="passworda" />
      </td>
    </tr>
    <tr>
      <td class="label">Retype</td>
      <td>
      <input type="password" value="<?= decode(post('passwordb')) ?>" name="passwordb" />
      </td>
    </tr>
    <tr>
      <td class="label">E-Mail</td>
      <td>
      <input type="text" value="<?= decode(post('email')) ?>" name="email" />
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