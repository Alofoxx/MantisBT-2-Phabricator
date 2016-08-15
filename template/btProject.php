<?php
/**
 * Draw body for btProject.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */

function drawbtProject(){
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
                    <th>Project Id</th>
                    <th>Project Name</th>
                    <th>Project Description</th>
                    <th>Import Bugs</th>
                  </tr>
                </thead>
                <tbody>
EOD;

foreach ($_SESSION['phab']['projects'] as $project) {
  print '                  <tr>';
  print '                    <td>'.$project['id'].'</td>';
  print '                    <td><input type="text" class="form-control" value="'.$project['name'].'" name="'.$project['id'].'_name" /></td>';
  print '                    <td><input type="text" class="form-control" value="'.$project['description'].'" name="'.$project['id'].'_description" /></td>';
  print '                    <td><input type="radio" class="" value="1" name="'.$project['id'].'_enabled" '.($project['enabled'] ? 'checked' : '').' /> Yes &nbsp;&nbsp;'.
                                '<input type="radio" class="" value="0" name="'.$project['id'].'_enabled" '.($project['enabled'] ? '' : 'checked').' /> No</td>';
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
