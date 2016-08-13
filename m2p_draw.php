<?php
/**
 * Main functions for rendering the html.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */

function drawTop($header){
  print <<<EOD
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>MantisBT-2-Phabricator</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="template/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="template/css/dashboard.css" rel="stylesheet">

    <!-- Custom styles for breadcrumbs -->
    <link href="template/css/breadcrumb.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">MantisBT-2-Phabricator</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="index.php?action=reset">Start Over</a></li>
            <li><a href="#">Settings</a></li>
            <li><a target="_blank" href="https://github.com/Alofoxx/MantisBT-2-Phabricator">Source by Alofoxx</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 main">
          <h1 class="page-header">{$header}</h1>


EOD;
  return;
}


function drawBottom(){
  //debug printing.
  if(M2P_DEBUG){
    print "<br /> <br /> <br /> <br /> <br /> <pre>";
    print "<br /> /*---------( M2P_CONFIG )-----------------------------*/ <br />";
    print_r($GLOBALS['M2P_CONFIG']);
    print "<br /> /*---------( _POST )-----------------------------*/ <br />";
    print_r($_POST);
    print "<br /> /*---------( _SESSION )-----------------------------*/ <br />";
    print_r($_SESSION);
    print "</pre>";
  }

  print <<<EOD

    </div>
  </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="template/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
EOD;
  return;
}
