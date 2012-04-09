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
		$form['guardianname']=isset($person->guardianname)?$person->guardianname:'';
		$form['guardianemail']=isset($person->guardianemail)?$person->guardianemail:'';
		$form['guardiantelno']=isset($person->guardiantelno)?$person->guardiantelno:'';
		$form['notes']=isset($person->notes)?$person->notes:'';
		$errors=array();
		if(isset($_POST['guardianconfirm'])&&$_POST['guardianconfirm']==1){
			try{
				foreach($form as $f=>$v){
					$person->set($f,(isset($_POST[$f]) ? $_POST[$f]:$form[$f]));
					$form[$f]=( isset($_POST[$f]) ? $_POST[$f]:$form[$f]);
				}
				$person->set("guardianconfirm",1);
				$person->save();
			}catch(Exception $e){
				$errors[]=$e->getMessage();
			}
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
      	<form action="?person=<?PHP echo isset($_GET['person'])?$_GET['person']:''; ?>" method="post" id="login" class="form-horizontal">
        <fieldset>
          <legend>Modify Guardian Settings</legend>
          <div class="control-group">
            <label class="control-label" for="name">Full Name</label>
            <div class="controls">
              <span class="input-xlarge uneditable-input"><?PHP echo $form['name']; ?></span>
            </div>
          </div>
          

          <div class="control-group">
            <label class="control-label" for="guardianname">Guardian Name</label>
            <div class="controls">
                  <input type="text" class="input-xlarge" id="guardianname" name="guardianname" value="<?PHP echo $form['guardianname']; ?>">
            </div>
          </div>


          <div class="control-group">
            <label class="control-label" for="guardianemail">Guardian Email</label>
            <div class="controls">
                  <input type="email" class="input-xlarge" id="guardianemail" name="guardianemail" value="<?PHP echo $form['guardianemail']; ?>">
            </div>
          </div>

          <div class="control-group">
            <label class="control-label" for="guardiantelno">Guardian Tel.</label>
            <div class="controls">
              <div class="input-prepend">
                  <span class="add-on">+44</span><input type="tel" class="input-large" id="guardiantelno" name="guardiantelno" value="<?PHP echo $form['guardiantelno']; ?>">
              </div>
            </div>
          </div>

          
          <div class="control-group">
            <label class="control-label" for="notes">Notes</label>
            <div class="controls">
              <textarea class="input-xlarge" id="notes" name="notes" rows="6"><?PHP echo $form['notes']; ?></textarea>
            </div>
          </div>
          
            <div class="control-group">  
                <div class="controls">
                    <!--<select multiple="multiple" id="competitions" name="competitions">
                        <option>1</option>  
                        <option>2</option>  
                        <option>3</option>  
                        <option>4</option>  
                        <option>5</option>  
                    </select>  -->
                    <p class="help-block">
                    By submitting this form, you are confirming that the elected parent or guardian has expressed permission for their child to participate.
                    </p>
                </div>  
            </div>  
          
          <div class="form-actions">
          <input type="hidden" id="guardianconfirm" name="guardianconfirm" value="1">
            <button type="submit" class="btn btn-primary">Save and Confirm Parent/Guardian</button>
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
