<?PHP

require_once("../data/backend.php");

$page=new Page("mentors/basicmap.php","Mentors");

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
    <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
			<script type="text/javascript">
          var london = new google.maps.LatLng(54.111057,-3.227492);
        
          var neighborhoods = [<?PHP
					
					
					$pc=new MentorCollection();
					
					$pc->pagelength=10000;
					$pc->loadActiveMentorsPage(1);
					
					$i=0;
					foreach($pc->entities as $entity){
						
						if(isset($entity->latlng)){
							if($i++!=0) echo ',';
							echo 'new google.maps.LatLng'.$entity->latlng;
						}
						
					}
					
					?>
         ];
		 
		  var diff = [<?PHP
					
					$i=0;
					foreach($pc->entities as $entity){
						
						if(isset($entity->latlng)){
							if($i++!=0) echo ',';
							echo isset($entity->raw->centres)?1:0;
						}
						
					}
					
					?>]
          var markers = [];
          var iterator = 0;
        
          var map;
        
          function maps_initialize() {
            var mapOptions = {
              zoom: 6,
              mapTypeId: google.maps.MapTypeId.ROADMAP,
              center: london
            };
        
            map = new google.maps.Map(document.getElementById("map_canvas"),
                    mapOptions);
					
					maps_drop();
          }
        
          function maps_drop() {
            for (var i = 0; i < neighborhoods.length; i++) {
              setTimeout(function() {
                addMarker();
              }, i * 300);
            }
          }
        
          function addMarker() {
			  var an;
			  if(diff[iterator]==0) an=google.maps.Animation.BOUNCE;
			  else an=google.maps.Animation.DROP;
            markers.push(new google.maps.Marker({
              position: neighborhoods[iterator],
              map: map,
              draggable: false,
              animation: an
            }));
            iterator++;
          }
        </script>
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../interface/bootstrap/images/favicon.ico">
    <link rel="apple-touch-icon" href="../interface/bootstrap/images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../interface/bootstrap/images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../interface/bootstrap/images/apple-touch-icon-114x114.png">
  </head>
  <body onLoad="maps_initialize()">
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
				<?PHP echo $page->drawSubNavigation(); ?></div>
                
                <div class="well">
<?PHP echo $page->drawMilestone(); ?>
                </div>
            </div>
            <div class="span10">
                  <!--Body content-->
                  
                  <div id="map_canvas" style="height: 600px;">map div</div>
                    
                    
               
                  
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
