<?php
/**
 * Draw body for drawbtBugs.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */

function drawbtBugs(){
  print <<<EOD
          <p> These are some instructions. </p>


          <form action="index.php" method="post" name="" id="">
            <h3>Tickets found:
EOD;

  $count = 0;
  foreach ($_SESSION['phab']['tickets'] as $projectTickets){
    $count = $count + count($projectTickets);
  }
  print " $count </h3>";

  print <<<EOD

            <br />
            <input type="submit" name="button" class="btn btn-primary pull-right" value="Save and Continue">
          </form>
          <input type="submit" class="btn btn-default pull-left" value="Back">

EOD;
  return;
}
