<?php
/**
 * Draw body for drawbtCustomField.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */

function drawbtCustomField(){
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
                    <th>Custom Field Id</th>
                    <th>Custom Field Name</th>
                  </tr>
                </thead>
                <tbody>
EOD;

  foreach ($_SESSION['phab']['customFields'] as $row){
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
