<?PHP

require_once("../data/backend.php");

$page=new Page("teams/all.php","Teams");

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Young Rewired State</title>
    <meta name="description" content="A quick demo of the sheer power of Furion.">
    <meta name="author" content="GridFusions.com / Lawrence Job">
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le styles -->
    <link href="../interface/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
       /* padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
	  @media (max-width: 768px) {

		  /* Remove any padding from the body */
		  body {
			padding-top: 0px !important;
		  }
	  }
    </style>
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../interface/bootstrap/images/favicon.ico">
    <link rel="apple-touch-icon" href="../interface/bootstrap/images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../interface/bootstrap/images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../interface/bootstrap/images/apple-touch-icon-114x114.png">
  </head>
  <body>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Young Rewired State</a>
          <div class="nav-collapse">
            <?PHP echo $page->drawNavigation(); ?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2">
                <div class="subnav subnav-fixed well" style="padding:8px 0">
				<?PHP echo $page->drawSubNavigation(); ?>                </div>
                
                <div class="well">
<?PHP echo $page->drawMilestone(); ?>
                </div>
            </div>
            <div class="span10">
                  <!--Body content-->
                  
<h2>The Future (Page Not Finished!)</h2>
<p>Imagine a YRS where users signed up with their teams as part of joining a centre. Imagine that same world where a programme could write itself for you,
meaning that you don't have to worry about scheduling and managing presentations. The software could even predict the length of the presentations and share
live information with the audience's smartphones through a web app. </p>
<p>I can write it for you, but I'm really rather running out of time for now. I'm slightly over-budget, given that this was only meant to take three days.
I'll be free again soon enough (possibly very soon if I can make the calls quickly enough).</p>
<p>In the mean time, please do explain to those who are curious why the software is useful. It suits the image I'm trying to convey to the world through my
work and through Furion: the cloud can easily automate many a workplace and significantly reduce workloads. This software was also built with the aim of
improving collaboration between people working on the data and those who enter the data.</p>
<p>I really do hope this has helped.</p>
<p>Lawrence</p>                  
                  <!--/body content-->      
                  
            </div>
        </div>
    </div>

    </div> <!-- /container -->
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../interface/bootstrap/js/jquery.js"></script>
    <?PHP echo $page->getFooterJS(); ?>
    <script src="../interface/bootstrap/js/bootstrap.js"></script>
  </body>
</html>
