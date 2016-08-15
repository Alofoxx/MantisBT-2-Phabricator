<?php
/**
 * Draw body for btVersion.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */

function drawbtVersion(){
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
                    <th>Version Id</th>
                    <th>Poject Id</th>
                    <th>Version</th>
                    <th style="width: 17em;">Is Milestone?</th>
                  </tr>
                </thead>
                <tbody>
EOD;

  foreach ($_SESSION['phab']['versions'] as $project){
    foreach ($project as $row) {
      print '                  <tr>';
      print '                    <td>'.$row['id'].'</td>';
      print '                    <td>'.$row['project_id'].'</td>';
      print '                    <td><input type="text" class="form-control" value="'.$row['version'].'" name="'.$row['id'].'_version" /></td>';
      print '                    <td><input type="radio" class="" value="1" name="'.$row['id'].'_milestone" '.($row['milestone'] ? 'checked' : '').' /> Milestone &nbsp;&nbsp;'.
                                    '<input type="radio" class="" value="0" name="'.$row['id'].'_milestone" '.($row['milestone'] ? '' : 'checked').' /> New Phab Project</td>';
      print '                  </tr>';
    }
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
