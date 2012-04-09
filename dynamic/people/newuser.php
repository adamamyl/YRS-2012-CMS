<?PHP

require_once("../data/backend.php");

$page=new Page("people/newuser.php","People");

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
                  
			<?PHP 
            
		
			if(isset($_POST['name'])){
				$person=new Person();
				
				try {
					foreach($person->sd as $local=>$field){
						if($field['name']!='id'){
							if(!isset($_POST[$field['name']])){
								if(in_array('required',$field['flags']))
									throw new FormException("Required field '".$field['name']."' is missing");
							} else 
								$person->set($local,$_POST[$field['name']]);
						}
					}
					$person->save();
					$person->addManyMany("peoplecompetitions","competitions",3);
					
					header("Location: ./basiclist.php");

				} catch (Exception $e){
					echo ("There was a problem submitting your form.".$e);
				}
			}
            
            ?>

    <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript">
		var mapcentre = new google.maps.LatLng<?PHP if(isset($_POST['latlng'])) echo $_POST['latlng']; else echo "(51.507222, -0.1275)"; ?>;
		var markerloc = new google.maps.LatLng<?PHP if(isset($_POST['latlng'])) echo $_POST['latlng']; else echo "(51.507222, -0.1275)"; ?>;
		var marker;
		var map;
		
		function init_map() {
			var mapOptions = {
				zoom: 8,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: mapcentre
			};
			
			map = new google.maps.Map(document.getElementById("map_canvas"),
					mapOptions);
				  
			marker = new google.maps.Marker({
				map:map,
				draggable:true,
				animation: google.maps.Animation.DROP,
				position: markerloc
			});
			//google.maps.event.addListener(marker, 'click', toggleBounce);
			
			google.maps.event.addListener(marker, 'position_changed', markerMoved);
		}
		
		function toggleBounce() {
			
			if (marker.getAnimation() != null) {
				marker.setAnimation(null);
			} else {
				marker.setAnimation(google.maps.Animation.BOUNCE);
			}
		}
		
		function markerMoved() {
			$('#latlng').val(marker.getPosition());
		}

		
	</script>
      	<form action="?save=new" method="post" id="login" class="form-horizontal">
        <fieldset>
          <legend>Add a Participant</legend>
          <div class="control-group">
            <label class="control-label" for="name">Full Name</label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="name" name="name" value="<?PHP if(isset($_POST['name'])) echo $_POST['name']; ?>">
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label" for="email">Email Address</label>
            <div class="controls">
              <input type="email" class="input-xlarge" id="email" name="email" value="<?PHP if(isset($_POST['email'])) echo $_POST['email']; ?>">
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label" for="telno">Telephone Number</label>
            <div class="controls">
              <div class="input-prepend">
                  <span class="add-on">+44</span><input type="tel" class="input-xlarge" id="telno" name="telno" value="<?PHP if(isset($_POST['telno'])) echo $_POST['telno']; ?>">
              </div>
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label" for="birthmonth">Birth Month</label>
            <div class="controls">
<select name="birthmonth" id="birthmonth"><option value="January">January</option> <option value="February">February</option> <option value="March">March</option> <option value="April">April</option> <option value="May">May</option> <option value="June">June</option> <option value="July">July</option> <option value="August">August</option> <option value="September">September</option> <option value="October">October</option> <option value="November">November</option> <option value="December">December</option></select>
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label" for="birthyear">Birth Year</label>
            <div class="controls">
<select name="birthyear" id="birthyear"><option value="1992">1992</option> <option value="1993">1993</option> <option value="1994">1994</option> <option value="1995">1995</option> <option value="1996">1996</option> <option value="1997">1997</option> <option value="1998">1998</option> <option value="1999">1999</option> <option value="2000">2000</option> <option value="2001">2001</option> <option value="2002">2002</option> <option value="2003">2003</option> <option value="2004">2004</option> <option value="2005">2005</option> <option value="2006">2006</option> <option value="After 2006">After 2006</option></select>
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label" for="techexperience">Tech Experience</label>
            <div class="controls">
              <textarea class="input-xlarge" id="techexperience" name="techexperience" rows="6"><?PHP if(isset($_POST['techexperience'])) echo $_POST['techexperience']; ?></textarea>
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label" for="city">Nearest City</label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="city" name="city" value="<?PHP if(isset($_POST['city'])) echo $_POST['city']; ?>">
            </div>
          </div>      
              
          <div class="control-group">
            <label class="control-label" for="city">Geography</label>
            <div class="controls">
          		<div id="map_canvas" style="width: 500px; height: 400px;">map div</div>
                <input type="hidden" class="input-xlarge" id="latlng" name="latlng" value="<?PHP if(isset($_POST['latlng'])) echo $_POST['latlng']; else echo "(51.507218661100346, -0.12750000000005457)"; ?>">
              <p class="help-block">Drop the pin (very roughly) to represent the area in which you live.</p>
            </div>
          </div>
          
          <style type="text/css">
		  #map_canvas img {
			max-width: none;
			}
		  </style>
          
                        
          <div class="control-group">
            <label class="control-label" for="twitter">Twitter Username</label>
            <div class="controls">
              <div class="input-prepend">
                  <span class="add-on">@</span><input type="text" class="input-xlarge" id="twitter" name="twitter" value="<?PHP if(isset($_POST['twitter'])) echo $_POST['twitter']; ?>">
              </div>
            </div>
          </div>
          
          <div class="control-group">
            <label class="control-label" for="referrer">Referrer</label>
            <div class="controls">
                  <input type="text" class="input-xlarge" id="referrer" name="referrer" value="<?PHP if(isset($_POST['referrer'])) echo $_POST['referrer']; ?>">
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="guardianname">Guardian Name</label>
            <div class="controls">
                  <input type="text" class="input-xlarge" id="guardianname" name="guardianname" value="<?PHP if(isset($_POST['guardianname'])) echo $_POST['guardianname']; ?>">
            </div>
          </div>


          <div class="control-group">
            <label class="control-label" for="guardianemail">Guardian Email</label>
            <div class="controls">
                  <input type="email" class="input-xlarge" id="guardianemail" name="guardianemail" value="<?PHP if(isset($_POST['guardianemail'])) echo $_POST['guardianemail']; ?>">
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="guardiantel">Guardian Tel.</label>
            <div class="controls">
              <div class="input-prepend">
                  <span class="add-on">+44</span><input type="tel" class="input-xlarge" id="guardiantel" name="guardiantel" value="<?PHP if(isset($_POST['guardiantel'])) echo $_POST['guardiantel']; ?>">
              </div>
            </div>
          </div>

          
          <div class="control-group">
            <label class="control-label" for="notes">Notes</label>
            <div class="controls">
              <textarea class="input-xlarge" id="notes" name="notes" rows="3"><?PHP if(isset($_POST['notes'])) echo $_POST['notes']; ?></textarea>
            </div>
          </div>
          
            <div class="control-group">  
                <label class="control-label" for="competitions">Competition</label>  
                <div class="controls">
                    <!--<select multiple="multiple" id="competitions" name="competitions">
                        <option>1</option>  
                        <option>2</option>  
                        <option>3</option>  
                        <option>4</option>  
                        <option>5</option>  
                    </select>  -->
                    <p id="competitions" class="help-block">
                    YRS 2012
                    </p>
                </div>  
            </div>  
          
          <div class="form-actions">
            <button type="submit" class="btn btn-primary">To Infinity and Beyond!</button>
          </div>
        </fieldset>
        </form>
                  
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
    <script type="text/javascript">
		$(document).ready(init_map);
	</script>
    <script src="../interface/bootstrap/js/bootstrap.js"></script>
  </body>
</html>
