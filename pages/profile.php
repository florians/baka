<div class="left">
  <div class="userinfo">
    <h1>Profile</h1>
    <table>
      <tr>
        <td class="label">Firstname</td>
        <td><?= $this -> user -> getFirstname() ?></td>
      </tr>
      <tr>
        <td class="label">Lastname</td>
        <td><?= $this -> user -> getLastname() ?></td>
      </tr>
      <tr>
        <td class="label">Username</td>
        <td><?= $this -> user -> getUsername() ?></td>
      </tr>
      <tr>
        <td class="label">E-Mail</td>
        <td><?= $this -> user -> getEmail() ?></td>
      </tr>
    </table>
  </div>
</div>
<div class="right">
  <div class="statistic">
    <h1>Statistic</h1>
    <table>
      <tr>
        <td class="label">Fights done</td>
        <td>20</td>
      </tr>
      <tr>
        <td class="label">Won</td>
        <td>10</td>
      </tr>
      <tr>
        <td class="label">Lose</td>
        <td>10</td>
      </tr>
    </table>
  </div>
</div>