<?php
/**
 * Draw body for btUser.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */

function drawbtUser(){
  print <<<EOD
          <p> These are some instructions. </p>

          <input type="submit" class="btn btn-default pull-left" value="Back">
          <form action="index.php" method="post" name="" id="">
            <input type="submit" name="button" class="btn btn-primary pull-right" value="Save and Continue">
            <br /><br /><br />
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>User Id</th>
                    <th>Username</th>
                    <th>Realname</th>
                    <th>Title</th>
                    <th>Email</th>
                    <th>Enabled</th>
                  </tr>
                </thead>
                <tbody>
EOD;

foreach ($_SESSION['phab']['users'] as $user) {
  print '                  <tr>';
  print '                    <td>'.$user['id'].'</td>';
  print '                    <td><input type="text" class="form-control" value="'.$user['username'].'" name="'.$user['id'].'_username" /></td>';
  print '                    <td><input type="text" class="form-control" value="'.$user['realname'].'" name="'.$user['id'].'_realname" /></td>';
  print '                    <td><input type="text" class="form-control" value="'.$user['title'].'" name="'.$user['id'].'_title" /></td>';
  print '                    <td><input type="text" class="form-control" value="'.$user['email'].'" name="'.$user['id'].'_email" /></td>';
  print '                    <td><input type="radio" class="" value="1" name="'.$user['id'].'_enabled" '.($user['enabled'] ? 'checked' : '').' /> Yes &nbsp;&nbsp;'.
                                '<input type="radio" class="" value="0" name="'.$user['id'].'_enabled" '.($user['enabled'] ? '' : 'checked').' /> No</td>';
  print '                  </tr>';
}

  print <<<EOD
                </tbody>
              </table>
            </div>
            <br />
            <input type="submit" name="button" class="btn btn-primary pull-right" value="Save and Continue">
          </form>
          <input type="submit" class="btn btn-default pull-left" value="Back">

EOD;
  return;
}
