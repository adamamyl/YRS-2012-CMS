<?PHP

require_once("../data/backend.php");

$page=new Page("centres/basiclist.php","Centres");

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
                        <th scope="col">Key Mentor</th>
                        <th scope="col">Telephone</th>
                        <th scope="col">City</th>
                        <th scope="col">Description</th>
                        <th scope="col">Options</th>
                      </tr>
                    </thead>
                    <tbody>
                    
                    <?PHP
					
					
					$pc=new CentreCollection();
					
					if(isset($_GET['page'])) $pc->loadActiveCentresPage((int)$_GET['page']);
					else $pc->loadActiveCentresPage(1);
					
					//if(isset($_GET['page'])) $pc->pageno=$_GET['page'];
					
					foreach($pc->entities as $entity){
						$flags=' ';
						if(!isset($entity->contacttel)||strlen($entity->contacttel)<5) $flags.=' <a href="edit.php?centre='.$entity->id.'" class="label label-important">No telephone</a>';
						if(!isset($entity->latlng)||strlen($entity->latlng)<5) $flags.=' <a href="edit.php?centre='.$entity->id.'" class="label label-important">No geo</a>';
						
						if(isset($entity->notes)&&strlen($entity->notes)>3) $flags.=' <a href="edit.php?centre='.$entity->id.'" class="label label-info">Notes</a>';
						if(isset($entity->raw->status)&&(int)($entity->raw->status)>1) $flags.=' <a href="edit.php?centre='.$entity->id.'" class="label label-success">YRS 2012</a>';
						
						
						echo '<tr>
                        <th scope="row"><a href="edit.php?centre='.$entity->id.'">'.$entity->name.'</a></th>
                        <td><a href="../mentors/edit.php?mentor='.$entity->contactmentor.'" class="btn btn-mini btn-primary">View / Edit Contact</a></td>
                        <td>+44 '.$entity->contacttel.'</td>
                        <td>'.$entity->city.'</td>
                        <td>'.$entity->description.'</td>
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
