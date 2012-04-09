<?PHP

require_once("backend.php");

switch($_GET['type']){
	case 'person':

		$person=new Person();
		
		foreach($person->sd as $local=>$field){
			
			try {
			if($field['name']!='id'){
				if(!isset($_POST[$field['name']])){
					if(in_array('required',$field['flags']))
						throw new FormException("Required field '".$field['name']."' is missing");
				}else 
					$person->set($local,$_POST[$field['name']]);
			}
			} catch (Exception $e){
				die("form error.".$e);
			}
		}
		
		$person->save();
		
		$person->addManyMany("peoplecompetitions","competitions",3);
		
	break;
	case 'mentor':

		$mentor=new Mentor();
		
		foreach($mentor->sd as $local=>$field){
			
			try {
			if($field['name']!='id'){
				if(!isset($_POST[$field['name']])){
					if(in_array('required',$field['flags']))
						throw new FormException("Required field '".$field['name']."' is missing");
				}else 
					$mentor->set($local,$_POST[$field['name']]);
			}
			} catch (Exception $e){
				die("form error.".$e);
			}
		}
		
		$mentor->save();
	break;
	case 'centre':
		$centre=new Centre();
		
		foreach($centre->sd as $local=>$field){
			
			try {
			if($field['name']!='id'){
				if(!isset($_POST[$field['name']])){
					if(in_array('required',$field['flags']))
						throw new FormException("Required field '".$field['name']."' is missing");
				}else 
					$centre->set($local,$_POST[$field['name']]);
			}
			} catch (Exception $e){
				die("form error.".$e);
			}
			
		}
		
		$centre->save();
	break;
}