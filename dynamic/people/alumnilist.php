<?PHP

require_once("../data/backend.php");

$page=new Page("people/alumnilist.php","People");

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
                  
                  <table class="table" border="0" cellspacing="0" cellpadding="0">
                  	<thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Tel.</th>
                        <th scope="col">D.O.B.</th>
                        <th scope="col">City</th>
                        <th scope="col">Twitter</th>
                        <th scope="col">Centre</th>
                      </tr>
                    </thead>
                    <tbody>
                    
                    <?PHP
					
					
					$pc=new PersonCollection();
					
					if(isset($_GET['page'])) $pc->loadPage((int)$_GET['page']);
					else $pc->loadPage(1);
					
					foreach($pc->entities as $entity){
						$flags=' ';
						
						if(isset($entity->twitter)&&strlen($entity->twitter)>0) $twitterrow='<a href="http://twitter.com/'.preg_replace("/[^a-zA-Z0-9\s]/", "", $entity->twitter).'">@'.preg_replace("/[^a-zA-Z0-9\s]/", "", $entity->twitter).'</a>';
						else $twitterrow="";
						
						if(!isset($entity->telno)||strlen($entity->telno)<5) $flags.=' <span class="label label-important">No telephone</span>';
						
						if(isset($entity->notes)&&strlen($entity->notes)>3) $flags.=' <span class="label label-info">Notes</span>';
						
						echo '<tr>
                        <th scope="row">'.$entity->name.'</th>
                        <td><a href="mailto:'.$entity->email.'">'.$entity->email.'</a></td>
                        <td>+44 '.$entity->telno.'</td>
                        <td>'.$entity->birthmonth.' '.$entity->birthyear.'</td>
                        <td>'.$entity->city.'</td>
                        <td>'.$twitterrow.'</td>
                        <td> '.$flags.'</td>
                      </tr>';
						
					}
					
					?>
                    </tbody>
                  </table>
					
                    <?PHP echo $pc->generatePagination($page); ?>
                  
                  <!--/body content-->      
                  <footer>&copy; GridFusions 2012</footer>
            </div>
        </div>
    </div>

    </div> <!-- /container -->
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../interface/bootstrap/js/jquery.js"></script>
    <script src="../interface/bootstrap/js/bootstrap.js"></script>
  </body>
</html>
