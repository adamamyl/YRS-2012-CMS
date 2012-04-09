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

		$form['name']=isset($person->name)?$person->name:'';
		$form['notes']=isset($person->notes)?$person->notes:'';
		$errors=array();
		if(isset($_POST['formsubmit'])&&$_POST['formsubmit']==1){
			try{
				foreach($form as $f=>$v){
					$person->set($f,(isset($_POST[$f]) ? $_POST[$f]:$form[$f]));
					$form[$f]=( isset($_POST[$f]) ? $_POST[$f]:$form[$f]);
				}
				//$person->set("guardianconfirm",1);
				$person->save();
			}catch(Exception $e){
				$errors[]=$e->getMessage();
			}
			foreach($person->getManyMany("peoplecompetitions","people","") as $mm){
				try{
					$f="competitioncentre_".$mm->competitions;
					$person->setManyMany("peoplecompetitions",array("people"=>$person->id,"competitions"=>$mm->competitions),array("centres"=>(isset($_POST[$f]) ? $_POST[$f]:0)));
				}catch(Exception $e){
					$errors[]=$e->getMessage();
				}
			}

			if(count($errors)>0){
			$output='<div class="alert alert-warning"><strong>There were some problems submitting your form:</strong><ul>';
			foreach($errors as $error){
				$output.='<li>'.$error.'</li>';
			}
			$output.='</ul></div>';
			}else $output='<div class="alert alert-info">Your changes have been saved.</div>';
		}
		
		
		
		
		?>
          
          <?PHP
			
			if(isset($output)){
				echo $output;
			}
		  
		  ?>
      	<form action="?person=<?PHP echo isset($_GET['person'])?$_GET['person']:''; ?>" method="post" id="login" class="form-horizontal">
        <fieldset>
          <legend>Modify Person Settings</legend>
          <div class="control-group">
            <label class="control-label" for="name">Full Name</label>
            <div class="controls">
              <span class="input-xlarge uneditable-input"><?PHP echo $form['name']; ?></span>
            </div>
          </div>
          
          
          <?PHP echo $page->print_input("bigtext","notes","Notes",$form['notes']); ?>
          
        </fieldset>
        <fieldset>
          <legend>Choose a Centre</legend>
			<div class="control-group">
            <p>A list of recommended centres is shown below in order of closeness. Please note that the algorithm used to calculate displacement is <em>extremely</em> rough and neglects things like the curvature of the earth.</p>
            </div>

          <?PHP
          
		$competitions=array();
		
		$nll=$person->readFloatFromLatLng((string)$person->latlng);
		$centre_latitude=$nll['lat'];
		$centre_longitude=$nll['lng'];
		
		foreach($person->getManyMany("peoplecompetitions","people","") as $mm){
			$centres=array();
			$centreoptions=array("-- Unassigned --"=>0);
			$cc=new CentreCollection();
			$cc->loadActiveCentresPage(1,(int)$mm->competitions);
			foreach($cc->entities as $centre){
				$nll=$centre->readFloatFromLatLng((string)$centre->latlng);
				$offset_lat=(float)$nll['lat']-(float)$centre_latitude;
				$offset_lng=$nll['lng']-$centre_longitude;
				// ASSUMES FLAT PLANET DUE TO SMALL DISTANCES (Distances to, say, Iceland will be truly fff-incorrect).
				//$offsetunitangle=tan($offset_lat/$offset_lng)." degrees. <br />";
				$centre->offsetunitdisplacement=sqrt(pow($offset_lat,2)+pow($offset_lng,2));
				$centres[(int)$centre->id]=$centre;
				$centres2[(int)$centre->id]=$centre->offsetunitdisplacement;
				
			}
			asort($centres2);
			foreach($centres2 as $k=>$v){
				$centreoptions[$centres[$k]->name." (".$centres[$k]->city." -- ".round(68.46*$centres[$k]->offsetunitdisplacement,1)."km)"]=$centres[$k]->id;
			}
			$competitions[$mm->competitions]=$mm->centres;
			echo $page->print_input("select","competitioncentre_".$mm->competitions,"Centre for ".$page->competitions[$mm->competitions]->name,(int)$mm->centres,"Please select a centre. Highest is closest.",$centreoptions);
			unset($centreoptions);
		}
		   ?>
          
          <div class="form-actions">
          <input type="hidden" id="formsubmit" name="formsubmit" value="1">
            <button type="submit" class="btn btn-primary">Save and Confirm Centre</button>
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
    <script src="../interface/bootstrap/js/bootstrap.js"></script>
  </body>
</html>
