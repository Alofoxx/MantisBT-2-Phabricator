<?php
/**
 * Draw body for btCategory.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */

function drawbtCategory(){
  print <<<EOD
          <p> These are some instructions. </p>
          <p> Names will map to the existing Phabricator categories if they exist. </p>

          <input type="submit" class="btn btn-default pull-left" value="Back">
          <form action="index.php" method="post" name="" id="">
            <input type="submit" name="button" class="btn btn-primary pull-right" value="Save and Continue">
            <br /><br /><br />
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Category Id</th>
                    <th>Name</th>
                  </tr>
                </thead>
                <tbody>
EOD;

  foreach ($_SESSION['phab']['category'] as $row){
      print '                  <tr>';
      print '                    <td>'.$row['id'].'</td>';
      print '                    <td><input type="text" class="form-control" value="'.$row['name'].'" name="'.$row['id'].'_name" /></td>';
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
