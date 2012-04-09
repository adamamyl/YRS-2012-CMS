<?PHP

require_once("../data/backend.php");

$page=new Page("people/lookup.php","People");

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
		
		if(!isset($_GET['person'])) throw new Exception("No person selected");
		
		$person=new Person((int)$_GET['person']);

		foreach($person->sd as $local=>$field){
			if(in_array('id',$field['flags'])||in_array('fk',$field['flags'])||in_array('hidden',$field['flags'])){
				
				//if(!isset($_POST[$field['name']])){
					//if(in_array('required',$field['flags']))
						//throw new FormException("Required field '".$field['name']."' is missing");
				//} else 
				//	$person->set($local,$_POST[$field['name']]);
			}
			else {
				$form[$local]=isset($person->{$local})?$person->{$local}:'';
			}
		}
		$errors=array();
		if(isset($_POST['formsubmit'])&&$_POST['formsubmit']==1){
				foreach($form as $f=>$v){
			try{
					$person->set($f,(isset($_POST[$f]) ? $_POST[$f]:$form[$f]));}catch(Exception $e){
				$errors[]=$e->getMessage();
			}
					$form[$f]=( isset($_POST[$f]) ? $_POST[$f]:$form[$f]);
				}
				$person->save();
			
			if(count($errors)>0){
			$output='<div class="alert alert-warning"><strong>There were some problems submitting your form:</strong><ul>';
			foreach($errors as $error){
				$output.='<li>'.$error.'</li>';
			}
			$output.='</ul></div>';
			}else $output='<div class="alert alert-info">Your changes have been saved.
</div>';
		}
		
		function print_input($type,$fieldname,$name='',$helptext='',$seloptions=array()){
			global $form, $page;
			switch($type){
				case 'text':
				default:
			echo '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="'.$fieldname.'" name="'.$fieldname.'" value="'.$form[$fieldname].'">
              '.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'bigtext':
			echo '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
				<textarea class="input-xlarge" id="'.$fieldname.'" name="'.$fieldname.'" rows="3">'.$form[$fieldname].'</textarea>
				'.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'email':
			echo '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
              <input type="email" class="input-xlarge" id="'.$fieldname.'" name="'.$fieldname.'" value="'.$form[$fieldname].'">
              '.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'tel':
			echo '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
              <div class="input-prepend">
                  <span class="add-on">+44</span><input type="tel" class="input-large" id="'.$fieldname.'" name="'.$fieldname.'" value="'.$form[$fieldname].'">
              </div>
              '.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'twitter':
			echo '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
              <div class="input-prepend">
                  <span class="add-on">@</span><input type="text" class="input-large" id="'.$fieldname.'" name="'.$fieldname.'" value="'.$form[$fieldname].'">
              </div>
              '.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'select':
				default:
			echo '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
			<select id="'.$fieldname.'" name="'.$fieldname.'">';
			foreach($seloptions as $option=>$value){
				if($form[$fieldname]==$value) $selected=' selected="selected"';
				else $selected='';
				echo '<option value="'.$value.'" '.$selected.'>'.$option.'</option>';
			}
			echo'</select> '.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'latlng':
				if(++$page->mapsCount==1) echo '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>';
				?>
    <script type="text/javascript">
		var mapcentre_<?PHP echo $fieldname; ?> = new google.maps.LatLng<?PHP if(isset($form[$fieldname])&&strlen($form[$fieldname])>5) echo $form[$fieldname]; else echo "(51.507222, -0.1275)"; ?>;
		var markerloc_<?PHP echo $fieldname; ?> = new google.maps.LatLng<?PHP if(isset($form[$fieldname])&&strlen($form[$fieldname])>5) echo $form[$fieldname]; else echo "(51.507222, -0.1275)"; ?>;
		var marker_<?PHP echo $fieldname; ?>;
		var map_<?PHP echo $fieldname; ?>;
		
		function init_map_<?PHP echo $fieldname; ?>() {
			var mapOptions_<?PHP echo $fieldname; ?> = {
				zoom: 8,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: mapcentre_<?PHP echo $fieldname; ?>
			};
			
			map_<?PHP echo $fieldname; ?> = new google.maps.Map(document.getElementById("map_canvas_<?PHP echo $fieldname; ?>"),
					mapOptions_<?PHP echo $fieldname; ?>);
				  
			marker_<?PHP echo $fieldname; ?> = new google.maps.Marker({
				map:map_<?PHP echo $fieldname; ?>,
				draggable:true,
				animation: google.maps.Animation.DROP,
				position: markerloc_<?PHP echo $fieldname; ?>
			});
			//google.maps.event.addListener(marker_<?PHP echo $fieldname; ?>, 'click', toggleBounce_<?PHP echo $fieldname; ?>);
			
			google.maps.event.addListener(marker_<?PHP echo $fieldname; ?>, 'position_changed', markerMoved_<?PHP echo $fieldname; ?>);
		}
		
		function toggleBounce_<?PHP echo $fieldname; ?>() {
			
			if (marker_<?PHP echo $fieldname; ?>.getAnimation() != null) {
				marker_<?PHP echo $fieldname; ?>.setAnimation(null);
			} else {
				marker_<?PHP echo $fieldname; ?>.setAnimation(google.maps.Animation.BOUNCE);
			}
		}
		
		function markerMoved_<?PHP echo $fieldname; ?>() {
			$('#<?PHP echo $fieldname; ?>').val(marker_<?PHP echo $fieldname; ?>.getPosition());
		}
		
	</script>

          <div class="control-group">
            <label class="control-label" for="city">Geography</label>
            <div class="controls">
          		<div id="map_canvas_<?PHP echo $fieldname; ?>" style="width: 500px; height: 400px;">map div_<?PHP echo $fieldname; ?></div>
                <input type="hidden" class="input-xlarge" id="<?PHP echo $fieldname; ?>" name="<?PHP echo $fieldname; ?>" value="<?PHP if(isset($form[$fieldname])) echo $form[$fieldname]; ?>">
              <p class="help-block">Drop the pin (very roughly) to represent the area in which you live.</p>
            </div>
          </div>
          
          <style type="text/css">
		  #map_canvas_<?PHP echo $fieldname; ?> img {
			max-width: none;
			}
		  </style>
				
				
				
				<?PHP
				$page->addjQuery('ready','init_map_'.$fieldname);
				break;
			}
		}
		
		
		?>
          
          <?PHP
			
			if(isset($output)){
				echo $output;
			}
		  
		  ?>
      	<form action="?person=<?PHP echo isset($_GET['person'])?$_GET['person']:''; ?>" method="post" id="save" class="form-horizontal">
        <fieldset>
          <legend>Edit Person</legend>

          <?PHP print_input("text","name","Name"); ?>
          <?PHP print_input("email","email","Email Address"); ?>
          <?PHP print_input("tel","telno","Telephone"); ?>
          <?PHP print_input("select","birthmonth","Birth Month",null,array("January"=>"January","February"=>"February","March"=>"March","April"=>"April","May"=>"May","June"=>"June","July"=>"July","August"=>"August","September"=>"September","October"=>"October","November"=>"November","December"=>"December")); ?>
          <?PHP print_input("select","birthyear","Birth Year",null,array("1992"=>"1992","1993"=>"1993","1994"=>"1994","1995"=>"1995","1996"=>"1996","1997"=>"1997","1998"=>"1998","1999"=>"1999","2000"=>"2000","2001"=>"2001","2002"=>"2002","2003"=>"2003","2004"=>"2004","2005"=>"2005","2006"=>"2006","2006+"=>"2006+")); ?>
          <?PHP print_input("bigtext","techexperience","Tech Experience"); ?>
          <?PHP print_input("text","city","Nearest City"); ?>
          <?PHP print_input("latlng","latlng","Geography"); ?>
          <?PHP print_input("twitter","twitter","Twitter"); ?>
          <?PHP print_input("text","referrer","Referrer"); ?>
          <?PHP print_input("bigtext","notes","Notes"); ?>
          
          
          <div class="form-actions">
          <input type="hidden" id="formsubmit" name="formsubmit" value="1">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn">Reset</button>
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
    <?PHP echo $page->getFooterJS(); ?>
    <script src="../interface/bootstrap/js/bootstrap.js"></script>
  </body>
</html>
