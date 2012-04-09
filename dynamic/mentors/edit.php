<?PHP

require_once("../data/backend.php");

$page=new Page("mentors/lookup.php","Mentors");

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
		
		if(!isset($_GET['mentor'])) throw new Exception("No mentor selected");
		
		$mentor=new Mentor((int)$_GET['mentor']);

		foreach($mentor->sd as $local=>$field){
			if(in_array('id',$field['flags'])||in_array('fk',$field['flags'])||in_array('hidden',$field['flags'])){
				
				//if(!isset($_POST[$field['name']])){
					//if(in_array('required',$field['flags']))
						//throw new FormException("Required field '".$field['name']."' is missing");
				//} else 
				//	$mentor->set($local,$_POST[$field['name']]);
			}
			else {
				$form[$local]=isset($mentor->{$local})?$mentor->{$local}:'';
			}
		}
		$errors=array();
		if(isset($_POST['formsubmit'])&&$_POST['formsubmit']==1){
				foreach($form as $f=>$v){
			try{
					$mentor->set($f,(isset($_POST[$f]) ? $_POST[$f]:$form[$f]));}catch(Exception $e){
				$errors[]=$e->getMessage();
			}
					$form[$f]=( isset($_POST[$f]) ? $_POST[$f]:$form[$f]);
				}
				$mentor->save();
			
			if(count($errors)>0){
			$output='<div class="alert alert-warning"><strong>There were some problems submitting your form:</strong><ul>';
			foreach($errors as $error){
				$output.='<li>'.$error.'</li>';
			}
			$output.='</ul></div>';
			}else $output='<div class="alert alert-info">Your changes have been saved.
</div>';
		}
		
		?>
          
          <?PHP
			
			if(isset($output)){
				echo $output;
			}
		  
		  ?>
      	<form action="?mentor=<?PHP echo isset($_GET['mentor'])?$_GET['mentor']:''; ?>" method="post" id="save" class="form-horizontal">
        <fieldset>
          <legend>Edit Mentor</legend>

          <?PHP echo $page->print_input("text","name","Name",$form["name"]); ?>
          <?PHP echo $page->print_input("email","email","Email Address",$form["email"]); ?>
          <?PHP echo $page->print_input("tel","telno","Telephone",$form["telno"]); ?>
          <?PHP echo $page->print_input("bigtext","geekcred","Geeky Credentials",$form["geekcred"]); ?>
          <?PHP echo $page->print_input("text","city","Nearest City",$form["city"]); ?>
          <?PHP echo $page->print_input("latlng","latlng","Geography",$form["latlng"]); ?>
          <?PHP echo $page->print_input("twitter","twitter","Twitter",$form["twitter"]); ?>
          <?PHP echo $page->print_input("text","referrer","Referrer",$form["referrer"]); ?>
          <?PHP echo $page->print_input("bigtext","notes","Notes",$form["notes"]); ?>
          
          
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
