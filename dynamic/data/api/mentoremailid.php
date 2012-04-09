<?PHP




require_once("../backend.php");

$mentor=new Mentor();
try {
	$mentor->set('email',$_GET['email']);
	$mentor->search();
} catch (Exception $e){
	die(header($_SERVER['SERVER_PROTOCOL']." 400 Bad Request",true,400)."form error.".$e);
}

if(isset($mentor->id)){
	header("X-UserID: ".$mentor->id);
	echo $mentor->name;
} else die(header($_SERVER['SERVER_PROTOCOL']." 400 Bad Request",true,400).$e);