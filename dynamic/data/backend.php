<?PHP

class Page {
	
	public $competitions;
	
function __construct($c,$d){
	global $db;
	
	$this->currentpage=$c;
	$this->section=$d;
	$this->queryStringAssoc=$this->convertQueryString();
	$this->mapsCount=0;
	
	$this->navigation=array();
	$this->navigation['People']['Current Participants']['Map View']="people/basicmap.php";
	$this->navigation['People']['Current Participants']['List View']="people/basiclist.php";
	$this->navigation['People']['YRS Alumni']['List All']="people/alumnilist.php";
	$this->navigation['People']['Admin Stuff']['Add New']="people/newuser.php";
	$this->navigation['People']['Admin Stuff']['Look-up Person']="people/lookup.php";
	$this->navigation['Mentors']['Current Mentors']['Map View']="mentors/basicmap.php";
	$this->navigation['Mentors']['Current Mentors']['List View']="mentors/basiclist.php";
	$this->navigation['Mentors']['All Mentors']['List All']="mentors/alumnilist.php";
	$this->navigation['Mentors']['Admin Stuff']['Add New']="mentors/new.php";
	$this->navigation['Mentors']['Admin Stuff']['Look-up Person']="mentors/lookup.php";
	$this->navigation['Centres']['Current Centres']['Map View']="centres/basicmap.php";
	$this->navigation['Centres']['Current Centres']['List View']="centres/basiclist.php";
	$this->navigation['Centres']['All Centres']['List All']="centres/alumnilist.php";
	//$this->navigation['Centres']['Admin Stuff']['Add New']="centres/new.php";
	$this->navigation['Centres']['Admin Stuff']['Look-up Centre']="centres/lookup.php";
	$this->navigation['Teams']['All']['The Future']="teams/all.php";
	$this->navigation['Settings']['All']['All']="settings/all.php";
	
	$competitions=array();
	try{
		$res=$db->select(array("*"),array("competitions"),array(),10000);
		if($db->num_rows()==0) throw new Exception("No competitions installed.");
		
		foreach($res as $centres){
			$this->competitions[$centres->id]=$centres;
		}
	} catch (Exception $e){
		die("The database has not yet been installed.");
	}
}

function drawNavigation(){
	$output='<ul class="nav">';
	foreach($this->navigation as $name=>$content){
		if($name==$this->section) $class="active";
		else $class="";
		reset($content);
		reset($content[key($content)]);
		//echo $content[key($content)][key($content[key($content)])];
        $output.='<li class="'.$class.'"><a href="../'.$content[key($content)][key($content[key($content)])].'">'.$name.'</a></li>';
	}
	$output.='</ul>';
	return $output;
}

function drawSubNavigation(){
	
	$output='  
                    <ul class="nav nav-list">';
					
	$a=0;
	
	foreach($this->navigation[$this->section] as $name=>$nav){
		if(++$a!=1)
			$output.= '<li class="divider"></li>';
			
		$output.='<li class="nav-header">'.$name.'</li>';
		
		foreach($nav as $name=>$url){
			
			if($this->currentpage==$url)
				$class="active";
			else $class="";
			
			$output.='<li class="'.$class.'"><a href="../'.$url.'">'.$name.'</a></li>';
			
		}
	}
					
    $output.='     </ul>
				';
				
	return $output;
	
}

	function convertQueryString(){
		$op = array(); 
		if(isset($_SERVER['QUERY_STRING'])&&$_SERVER['QUERY_STRING']!=''){
		$pairs = explode("&", $_SERVER['QUERY_STRING']); 
		foreach ($pairs as $pair) {
			$a=explode("=",$pair);
			if(count($a)>1&&$a[0]!=''){
				$op[$a[0]] = $a[1]; 
			}
	//		list($k, $v) = array_map("urldecode", explode("=", $pair)); 
		//	if($k!='') $op[$k] = $v; 
		}}
		return($op);
	}

	function addToQueryString($array,$key,$value){
		$array[$key]=$value;
		return $array;
	}
	
	function getQueryString($vars){
		$output='';
		$v=array();
		foreach($this->queryStringAssoc as $key=>$value){
			$v[$key]=$value;
		}
		foreach($vars as $key=>$value){
			$v[$key]=$value;
		}
		foreach($v as $key=>$value){
			$output.=$key.'='.$value.'&';
		}
		return $output;
	}
	
	function addjQuery($event,$handler){
		$this->jQueryDocEvents[$event][]=$handler;
	}
	
	function getFooterJS(){
		if(!isset($this->jQueryDocEvents)) return;
		$output=' <script type="text/javascript">';
		foreach($this->jQueryDocEvents as $event=>$handlers){
			foreach ($handlers as $handler){
				$output.="
				$(document).".$event."(".$handler."); 
				";
			}
		}
		$output.='</script>';
		return $output;
	}
	
	function drawMilestone(){
		$pa=new PersonCollection();
		
		$pa->pagelength=10000;
		$pa->loadActivePeoplePage(1);
		
		$z=0;
		foreach($pa->entities as $entity){
			$z+=isset($entity->raw->centres)?1:0;
		}
		return '<div class="progress progress-info progress-striped"><div class="bar" style="width: '.($z/$pa->total)*100 .'%"></div></div>
		<p>'.$z.' of '.$pa->total.' YRS 2012 members have been assigned to centres.</p>';
    }
	
			function print_input($type,$fieldname,$name='',$value='',$helptext='',$seloptions=array(),$defaultvalue=''){
			global $form, $page;
			
			if($value==='') $value=$defaultvalue;
			switch($type){
				case 'text':
				default:
			$output= '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
              <input type="text" class="input-xlarge" id="'.$fieldname.'" name="'.$fieldname.'" value="'.$value.'">
              '.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'bigtext':
			$output= '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
				<textarea class="input-xlarge" id="'.$fieldname.'" name="'.$fieldname.'" rows="3">'.$value.'</textarea>
				'.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'email':
			$output= '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
              <input type="email" class="input-xlarge" id="'.$fieldname.'" name="'.$fieldname.'" value="'.$value.'">
              '.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'tel':
			$output= '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
              <div class="input-prepend">
                  <span class="add-on">+44</span><input type="tel" class="input-large" id="'.$fieldname.'" name="'.$fieldname.'" value="'.$value.'">
              </div>
              '.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'twitter':
			$output= '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
              <div class="input-prepend">
                  <span class="add-on">@</span><input type="text" class="input-large" id="'.$fieldname.'" name="'.$fieldname.'" value="'.$value.'">
              </div>
              '.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'select':
				default:
			$output= '          <div class="control-group">
            <label class="control-label" for="'.$fieldname.'">'.$name.'</label>
            <div class="controls">
			<select id="'.$fieldname.'" name="'.$fieldname.'">';
			foreach($seloptions as $option=>$val){
				if($value==$val) $selected=' selected="selected"';
				else $selected='';
				$output.= '<option value="'.$val.'" '.$selected.'>'.$option.'</option>';
			}
			$output.='</select> '.(($helptext)!=''?'<p class="help-block">'.$helptext.'</p>':'').'
            </div>
          </div>';
		  		break;
				case 'latlng':
				if(++$page->mapsCount==1) $output='<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false"></script>';
				if(isset($value)&&strlen($value)>5) $latlng2= $value; else $latlng2="(51.507222, -0.1275)";
				$output.='
    <script type="text/javascript">
		var mapcentre_'.$fieldname.' = new google.maps.LatLng'.$latlng2.';
		var markerloc_'.$fieldname.' = new google.maps.LatLng'.$latlng2.';
		';
		 $output.= "var marker_".$fieldname.";
		var map_".$fieldname.";
		
		function init_map_".$fieldname."() {
			var mapOptions_".$fieldname." = {
				zoom: 8,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: mapcentre_".$fieldname."
			};
			
			map_".$fieldname." = new google.maps.Map(document.getElementById('map_canvas_".$fieldname."'),
					mapOptions_".$fieldname.");
				  
			marker_".$fieldname." = new google.maps.Marker({
				map:map_".$fieldname.",
				draggable:true,
				animation: google.maps.Animation.DROP,
				position: markerloc_".$fieldname."
			});
			//google.maps.event.addListener(marker_".$fieldname.", 'click', toggleBounce_".$fieldname.");
			
			google.maps.event.addListener(marker_".$fieldname.", 'position_changed', markerMoved_".$fieldname.");
		}
		
		function toggleBounce_".$fieldname."() {
			
			if (marker_".$fieldname.".getAnimation() != null) {
				marker_".$fieldname.".setAnimation(null);
			} else {
				marker_".$fieldname.".setAnimation(google.maps.Animation.BOUNCE);
			}
		}
		
		function markerMoved_".$fieldname."() {
			$('#".$fieldname."').val(marker_".$fieldname.".getPosition());
		}";
		
		$output.='
	</script>

          <div class="control-group">
            <label class="control-label" for="city">Geography</label>
            <div class="controls">
          		<div id="map_canvas_'.$fieldname.'" style="width: 500px; height: 400px;">map div_'.$fieldname.'</div>
                <input type="hidden" class="input-xlarge" id="'.$fieldname.'" name="'.$fieldname.'" value="'.$value.'">
              <p class="help-block">Drop the pin (very roughly) to represent the area in which you live.</p>
            </div>
          </div>
          
          <style type="text/css">
		  #map_canvas_'.$fieldname.' img {
			max-width: none;
			}
		  </style>';
				
				$page->addjQuery('ready','init_map_'.$fieldname);
				break;
			}
			return $output;
		}

}


class Database {
	
	//--- PRIVATE VARIABLES ---
	//--------------------------------------------------
	
	private $db_connection; 		//Database Connection
	private $db_memcache = NULL;	//Memcache Connection
	private $db_last_result;		//Last MySQL Result
	
	
	//--- CONSTRUCTS ---
	//--------------------------------------------------
	
	//object $db = new database(string ADDRESS, string USERNAME, string PASSWORD, string DATABASE);
	function __construct($address = NULL, $username = NULL, $password = NULL, $database = NULL){
		if($address==NULL){return false;}
		if($username==NULL){return false;}
		//if($password==NULL){return false;}
		if($database==NULL){return false;}
		
		$this->db_connection = mysql_connect($address, $username, $password);
		if($this->db_connection===false){return false;}
		
		$db = mysql_select_db($database, $this->db_connection);
		if($db===false){return false;}
	}
	
	
	function __destruct(){
		$this->close();
	}
	
	
	//--- PUBLIC FUNCTIONS ---
	//--------------------------------------------------
	
	
	//boolean memcache(string ADDRESS, integer PORT);
	public function memcache($address = NULL, $port = 11211){
		if ($address == NULL) {return false;}
		
		//Memcache Extension NOT installed
		if (!class_exists('Memcache')) {return false;}
		
		$this->db_memcache = new Memcache;
		
		//Connect to Memcache server
		$res = $this->db_memcache->connect($address, $port);
	}
	
	
	//mysql result select(array FIELDS, array TABLES, array WHERE, integer LIMIT, boolean Memcache);
	public function select($fields = NULL, $tables = NULL, $where = NULL, $limit = NULL, $offset = NULL, $memcache = False, $memcache_expire = NULL){
		if($tables==NULL){return false;}
		
		$sql = 'SELECT ';
		
		if ($fields != NULL) {
			foreach($fields as $field){
				$sql .= mysql_real_escape_string($field) . ', ';
			}
			if (substr($sql, -2)==', ') {$sql = substr($sql, 0, -2);}
		} else {
			$sql .= '*';
		}
		
		$sql .= ' FROM ';
		
		foreach ($tables as $table) {
			$sql .= mysql_real_escape_string($table) . ', ';
		}
		if (substr($sql, -2)==', ') {$sql = substr($sql, 0, -2);}
		
		if ($where != NULL) {
			$sql .= ' WHERE (';
			
			foreach ($where as $key => $value) {
				$sql .='`'. mysql_real_escape_string($key) . '`=\'' . mysql_real_escape_string($value) . '\' AND ';
			}
			if (substr($sql, -5) == ' AND ') {$sql = substr($sql, 0, -5);}
			
			$sql .= ')';
		}
		
		if ($limit != NULL) {$sql .= ' LIMIT ' . (int)$limit;}
		if (($offset != NULL) && ($limit!=NULL)) {$sql .= ' OFFSET '. (int)$offset;}
		
		$sql .= ';';		//Finalise Query
		
		if (($this->db_memcache != NULL) && ($memcache === true)){
			$mem_result = $this->db_memcache->get(sha1($sql));
			
			if($mem_result !== false){
				return $mem_result;
			}
		}
		
		if ($q=$this->query($sql)) {
			//Save result in memcache
			if (($this->db_memcache != NULL) && ($memcache === true)){
				$this->db_memcache->set(sha1($sql), $this->result($q), NULL, $memcache_expire);
			}
			
			return $this->result($q);
		} else {
			return $this->error();
		}
		
		//return $this->select_custom();
	}


	//mysql result select(array FIELDS, array TABLES, array WHERE, integer LIMIT, boolean Memcache);
	public function select_custom($fields = NULL, $tables = NULL, $where = NULL, $limit = NULL, $offset = NULL, $memcache = False, $memcache_expire = NULL){
		if($tables==NULL){return false;}
		
		$sql = 'SELECT ';
		
		if ($fields != NULL) {
			foreach($fields as $field){
				$sql .= ($field) . ', ';
			}
			if (substr($sql, -2)==', ') {$sql = substr($sql, 0, -2);}
		} else {
			$sql .= '*';
		}
		
		$sql .= ' FROM ';
		
		foreach ($tables as $table) {
			$sql .= ($table) . ', ';
		}
		if (substr($sql, -2)==', ') {$sql = substr($sql, 0, -2);}
		
		if ($where != NULL) {
			$sql .= ' WHERE (';

			foreach ($where as $key => $value) {
				$sql .= ($key) . '=' . ($value) . ' AND ';
			}
			if (substr($sql, -5) == ' AND ') {$sql = substr($sql, 0, -5);}
			
			$sql .= ')';
		}
		
		if ($limit != NULL) {$sql .= ' LIMIT ' . (int)$limit;}
		if (($offset != NULL) && ($limit!=NULL)) {$sql .= ' OFFSET '. (int)$offset;}
		
		$sql .= ';';		//Finalise Query
		
		if (($this->db_memcache != NULL) && ($memcache === true)){
			$mem_result = $this->db_memcache->get(sha1($sql));
			
			if($mem_result !== false){
				return $mem_result;
			}
		}
		
		if ($q=$this->query($sql)) {
			//Save result in memcache
			if (($this->db_memcache != NULL) && ($memcache === true)){
				$this->db_memcache->set(sha1($sql), $this->result($q), NULL, $memcache_expire);
			}
			
			return $this->result($q);
		} else {
			return $this->error();
		}
	}
	
	
	//mysql update (array FIELDS=>VALUES, array TABLES, array WHERE, integer LIMIT);
	public function update($data = NULL, $tables = NULL, $where = NULL, $limit = NULL){
		if ($tables == NULL) {return false;}
		
		$sql = 'UPDATE ';
		
		foreach ($tables as $table) {
			$sql .= mysql_real_escape_string($table) . ', ';
		}
		
		if (substr($sql, -2)==', ') {$sql = substr($sql, 0, -2);}

		if ($data != NULL) {
			$sql .= ' SET ';
			
			foreach($data as $key => $value){
				$sql .='`'. mysql_real_escape_string($key) . '`=\'' . mysql_real_escape_string($value) . '\', ';
			}
			if(substr($sql, -2)==', '){$sql = substr($sql, 0, -2);}
			
			$sql .= '';
		} else return $this->error();


		if ($where != NULL) {
			$sql .= ' WHERE (';
			
			foreach ($where as $key => $value) {
				$sql .='`'. mysql_real_escape_string($key) . '`=\'' . mysql_real_escape_string($value) . '\' AND ';
			}
			if (substr($sql, -5) == ' AND ') {$sql = substr($sql, 0, -5);}
			
			$sql .= ')';
		}
		
		if ($limit != NULL) {$sql .= ' LIMIT ' . (int)$limit;}
		
		$sql .= ';';

		if ($this->query($sql) !== false) {
			return true;
		} else {
			return $this->error();
		}
	}
	
	
	//mysql insert(array FIELDS=>VALUES, array TABLES);
	public function insert($fields = NULL, $tables = NULL){
		if ($fields == NULL) {return false;}
		if ($tables == NULL) {return false;}
		
		$sql = 'INSERT INTO ';
		
		foreach ($tables as $table) {
			$sql .= mysql_real_escape_string($table) . ', ';
		}
		if (substr($sql, -2)==', ') {$sql = substr($sql, 0, -2);}
		
		$sql .= ' (';
		
		foreach ($fields as $key => $value){
			$sql .= '`' . mysql_real_escape_string($key) . '`, ';
		}
		if (substr($sql, -2)==', ') {$sql = substr($sql, 0, -2);}
		
		$sql .= ') VALUES(';
		
		foreach ($fields as $key => $value){
			$sql .= '\'' . mysql_real_escape_string($value) . '\', ';
		}
		if (substr($sql, -2)==', ') {$sql = substr($sql, 0, -2);}
		
		$sql .= ');';
		
		if ($this->query($sql) !== false) {
			return true;
		} else {
			return $this->error();
		}
	}
	
	
	//mysql result query(string QUERY);
	public function query($query){
		
		if(isset($this->debug)) echo $query;
		
		$query = mysql_query($query, $this->db_connection);
		
		$this->db_last_result = $query;
		
		return $query;
	}
	
	
	//array result(mysql result RESULT);
	public function result($result){
			$return = array();
		if(mysql_num_rows($result)>0){
			
			$i = 0;
			while($row = mysql_fetch_assoc($result)){
				$return[$i]=new stdClass();
				foreach($row as $key => $value){
					$return[$i]->$key = $value;
				}
				$i++;
			}
		}
		//nothing to return if nothing found!!!
		if(isset($return))return $return;
	}
	
	
	//integer num_rows();
	public function num_rows(){
		if($this->db_last_result===false)
		return 0;
		else return mysql_num_rows($this->db_last_result);
	}
	
	
	//boolean close();
	public function close(){
		return @mysql_close($this->db_connection);
	}
	
	//array $error = $db->error();
	public function error(){
		if(mysql_error($this->db_connection)){
			throw new DatabaseException(mysql_error($this->db_connection),mysql_errno($this->db_connection));
			return $error;
		} else {
			return false;
		}
	}
	
}

class DatabaseException extends Exception {
	
}

abstract class Collection {
	public $entities, $pagelength, $pages;
	
	function __construct(){
		
		//$this->type="People";
		// GIVE IT AN OFFSET AND A COUNT.
		// IT RETURNS A LIST OF OBJECTS.
		// IT ALSO RETURNS A TOTAL NUMBER OF OBJECTS
	}
	
	function load($count,$offset){
		global $db;
		$result=$db->select(array("*"),array($this->tablename),0,$count,$offset);
		$pages=$db->select(array("count(*)"),array($this->tablename),0,100000,0);
		$this->loadFromObjectArray($result);
		$this->pages=ceil(((int)$pages[0]->{"count(*)"})/$this->pagelength);
		$this->total=(int)$pages[0]->{"count(*)"};
	}
	
	function loadSearch($field,$value,$count,$offset){
		global $db;
		$result=$db->select(array("*"),array($this->tablename),array(mysql_escape_string($field)=>$value),$count,$offset);
		$pages=$db->select(array("count(*)"),array($this->tablename),array(mysql_escape_string($field)=>$value),100000,0);
		$this->loadFromObjectArray($result);
		$this->pages=ceil(((int)$pages[0]->{"count(*)"})/$this->pagelength);
		$this->total=(int)$pages[0]->{"count(*)"};
	}
	
	function loadPage($pageno){
		$this->load($this->pagelength,($pageno-1)*$this->pagelength);
		$this->pageno=$pageno;
	}
	
	function loadSearchPage($pageno,$field,$value){
		$this->loadSearch($field,$value,$this->pagelength,($pageno-1)*$this->pagelength);
		$this->pageno=$pageno;
	}
	
	function loadFromObjectArray($result){
		if(!isset($this->entities)) $this->entities=array();
		foreach($result as $res){
			$obj=new $this->type;
			$obj->loadFromObj($res);
			$obj->raw=$res;
			$this->entities[]=$obj;
		}
	}
	
	function generatePagination(&$page){
		$output='<div class="pagination pagination-centered"><ul>';
		$output.='<li><a class="active" href="?'.$page->getQueryString(array('page'=>1)).'">&laquo; First</a></li>';
		for($i=$this->pageno-3;$i<$this->pageno;$i++){
			if($i>0) 	$output.= '<li><a href="?'.$page->getQueryString(array('page'=>$i)).'">'.$i.'</a><li>';
		}
		$output.='<li class="active"><a href="#">'.$this->pageno.' of '.$this->pages.'</a></li>';
		for($i=$this->pageno+1;$i<$this->pageno+4;$i++){
			if($i<=$this->pages) $output.= '<li><a href="?'.$page->getQueryString(array('page'=>$i)).'">'.$i.'</a><li>';
		}
		$output.='<li><a href="?'.$page->getQueryString(array('page'=>$this->pages)).'">Last &raquo;</a></li>';
		$output.= '</ul></div>';
		
		return $output;
	}

}

class PersonCollection extends Collection{
	function __construct(){
		$this->type="Person";
		$this->tablename="people";
		$this->pagelength=20;
		$this->pageno=1;
		$this->pages=3;
	}
	
	function loadActivePeoplePage($pageno){
		$this->loadActivePeople($this->pagelength,($pageno-1)*$this->pagelength);
		$this->pageno=$pageno;
	}
		
	function loadActivePeople($limit,$offset){
		global $db;
		$result=$db->select_custom(array("*"),array("people","peoplecompetitions"),array("peoplecompetitions.people"=>"people.id","peoplecompetitions.competitions"=>3),$limit,$offset);
		$pages=$db->select_custom(array("count(*)"),array("people","peoplecompetitions"),array("peoplecompetitions.people"=>"people.id","peoplecompetitions.competitions"=>3));
		$this->pages=ceil(((int)$pages[0]->{"count(*)"})/$this->pagelength);
		$this->total=(int)$pages[0]->{"count(*)"};
		$this->loadFromObjectArray($result);
	}
}

class MentorCollection extends Collection{
	function __construct(){
		$this->type="Mentor";
		$this->tablename="mentors";
		$this->pagelength=20;
		$this->pageno=1;
		$this->pages=3;
	}
	
	function loadActiveMentorsPage($pageno){
		$this->loadActiveMentors($this->pagelength,($pageno-1)*$this->pagelength);
		$this->pageno=$pageno;
	}
		
	function loadActiveMentors($limit,$offset){
		global $db;
		$result=$db->select_custom(array("*"),array($this->tablename,"mentorscompetitions"),array("mentorscompetitions.mentors"=>$this->tablename.".id","mentorscompetitions.competitions"=>3),$limit,$offset);
		$pages=$db->select_custom(array("count(*)"),array($this->tablename,"mentorscompetitions"),array("mentorscompetitions.mentors"=>$this->tablename.".id","mentorscompetitions.competitions"=>3));
		$this->pages=ceil(((int)$pages[0]->{"count(*)"})/$this->pagelength);
		$this->total=(int)$pages[0]->{"count(*)"};
		$this->loadFromObjectArray($result);
	}
}

class CentreCollection extends Collection{
	function __construct(){
		$this->type="Centre";
		$this->tablename="centres";
		$this->pagelength=20;
		$this->pageno=1;
		$this->pages=3;
	}
	
	function loadActiveCentresPage($pageno,$active=3){
		$this->loadActiveCentres($this->pagelength,($pageno-1)*$this->pagelength,$active);
		$this->pageno=$pageno;
	}
		
	function loadActiveCentres($limit,$offset,$active=3){
		global $db;
		$result=$db->select_custom(array("*"),array($this->tablename,"centrescompetitions"),array("centrescompetitions.centres"=>$this->tablename.".id","centrescompetitions.competitions"=>$active),$limit,$offset);
		$pages=$db->select_custom(array("count(*)"),array($this->tablename,"centrescompetitions"),array("centrescompetitions.centres"=>$this->tablename.".id","centrescompetitions.competitions"=>$active));
		$this->pages=ceil(((int)$pages[0]->{"count(*)"})/$this->pagelength);
		$this->total=(int)$pages[0]->{"count(*)"};
		$this->loadFromObjectArray($result);
	}
	
	function loadExtendedCentresPage($pageno){
		$this->loadActiveCentres($this->pagelength,($pageno-1)*$this->pagelength);
		$this->pageno=$pageno;
	}
		
	function loadExtendedCentres($limit,$offset){
		global $db;
		$result=$db->select_custom(array("*"),array($this->tablename,"centrescompetitions"),array("centrescompetitions.centres"=>$this->tablename.".id"),$limit,$offset);
		$pages=$db->select_custom(array("count(*)"),array($this->tablename,"centrescompetitions"),array("centrescompetitions.centres"=>$this->tablename.".id"));
		$this->pages=ceil(((int)$pages[0]->{"count(*)"})/$this->pagelength);
		$this->total=(int)$pages[0]->{"count(*)"};
		$this->loadFromObjectArray($result);
	}
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////

abstract class Entity {
	
	function __construct($id=0){
		//		   VAR NAME HERE	FIELD IN DB
		$this->sd=array();
		
		if(isset($id)&&$id!=0){
			$this->loadFromDB($id);
		}
	}
	
	function loadFromDB($id){
		global $db;
		$result=$db->select(array("*"),array($this->tablename),array("id"=>$id),1);
		if($result==NULL) throw new Exception("You are looking for something that isn't here",404);
		return $this->loadFromObj($result[0]);
	}
	
	function search(){
		global $db;
		
		$searchdata=array();
		
		foreach($this->sd as $here=>$database){
			if(isset($this->{$here})) $searchdata[$database['name']]=$this->{$here};
		}
		
		$result=$db->select(array("*"),array($this->tablename),$searchdata,1);
		
		return $this->loadFromObj($result[0]);
	}
	
	function loadFromObj($obj){
		
		foreach($this->sd as $here=>$database){
			if(isset($obj->{$database['name']})) $this->{$here}=$obj->{$database['name']};
		}
		
		if(isset($obj->id)) $this->id=$obj->id;
		return true;
	}
	
	function save(){
		
		foreach($this->sd as $here=>$database){
			if(isset($this->{$here})) $savedata[$database['name']]=$this->{$here};
		}
		
		if(isset($this->id))
			$this->update($savedata);
		else $this->create($savedata);
	}
	
	public function set($key,$value){
		if(isset($this->sd[$key]))
		switch($this->sd[$key]['type']){
			default:
			case 'text':
				if(strlen($value) < (int)$this->sd[$key]['length'] && (!in_array('required',$this->sd[$key]['flags'])||strlen($value)>0))
					$this->{$key}=$value;
				else throw new FormException("Invalid text entered for '".$this->sd[$key]['friendlyname']."'.");
			break;
			case 'email':
				if($this->isValidEmail($value)||(strlen(trim($value))==0&&!in_array('required',$this->sd[$key]['flags'])))
					$this->{$key}=$value;
				else throw new FormException("Invalid email entered for '".$this->sd[$key]['friendlyname']."'.");
			break;
			case 'bool':
			
			case 'int':
				$this->{$key}=(int)$value;
			break;
			case 'price':
				//round to 2 dp in GBP
			case 'float':
				$this->{$key}=(float)$value;
			break;
		}
	}
	
	private function update($data){
		global $db;
		$db->update($data,array($this->tablename),array('id'=>$this->id),1);
	}
	
	private function create($data){
		global $db;
		$db->insert($data,array($this->tablename));
		$result=$db->select(array('id'),array($this->tablename),$data,1);
		$this->id=(int)$result[0]->id;
	}
	
	private function isValidEmail($email){
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex){
			$isValid = false;
		}
		else {
			
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			
			if ($localLen < 1 || $localLen > 64){
				// local part length exceeded
				$isValid = false;
			}
			else if ($domainLen < 1 || $domainLen > 255){
				// domain part length exceeded
				$isValid = false;
			} else if ($local[0] == '.' || $local[$localLen-1] == '.'){
				// local part starts or ends with '.'
				$isValid = false;
			} else if (preg_match('/\\.\\./', $local)){
				// local part has two consecutive dots
				$isValid = false;
			} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){
				// character not valid in domain part
				$isValid = false;
			} else if (preg_match('/\\.\\./', $domain)){
				// domain part has two consecutive dots
				$isValid = false;
			} else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',str_replace("\\\\","",$local))){
				// character not valid in local part unless 
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))){
					$isValid = false;
				}
			}
			if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))){
				// domain not found in DNS
				$isValid = false;
			}
		}
		return $isValid;
	}
	
	function readFloatFromLatLng($str){
		  $s_latlng=explode(",",$str);
		  
		  $s_lat=$s_latlng[0];
		  $s_lng=$s_latlng[1];
		  		  
		  return array("lat"=>(float)$s_lat = preg_replace("/[^a-zA-Z0-9.\s]/", "", $s_lat), "lng"=>(float)$s_lng = preg_replace("/[^a-zA-Z0-9.\s]/", "", $s_lng));
	}
	
	function addManyMany($table,$pk,$val){
		global $db;
		
		$insdata=array($this->tablename=>$this->id,$pk=>$val);
		
		$db->insert($insdata,array($table));
	}
	
	function getManyMany($table,$pk,$val){
		global $db;
		
		//$searchdata=array();
		
		return $db->select(array("*"),array($table),array($pk=>$this->id),10000);
	}
	
	function setManyMany($table,$keyvalues,$setvalues){
		global $db;
		$res=$db->update($setvalues,array($table),$keyvalues,1);

		//"peoplecompetitions",array("people"=>1,"competitions"=>$mm->competitions),(isset($_POST[$f]) ? $_POST[$f]:0)
	}

}

class Person extends Entity {
	
	function __construct($id=0){
		//		   VAR NAME HERE	FIELD IN DB
		$this->sd['birthmonth']=	array('name'=>'birthmonth',		'friendlyname'=>'Birth Month', 			'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['birthyear']=		array('name'=>'birthyear',		'friendlyname'=>'Birth Year', 			'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['city']=			array('name'=>'city',			'friendlyname'=>'City', 				'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['email']=			array('name'=>'email',			'friendlyname'=>'Email', 				'type'=>'email',	'length'=>45,		'flags'=>array("required"));
		$this->sd['latlng']=		array('name'=>'latlng',			'friendlyname'=>'Geography', 			'type'=>'latlng',	'length'=>45,		'flags'=>array("required"));
		$this->sd['name']=			array('name'=>'name',			'friendlyname'=>'Full Name', 			'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['notes']=			array('name'=>'notes',			'friendlyname'=>'Notes', 				'type'=>'text',		'length'=>255,		'flags'=>array());
		$this->sd['referrer']=		array('name'=>'referrer',		'friendlyname'=>'How You Found Us', 	'type'=>'text',		'length'=>45,		'flags'=>array());
		$this->sd['techexperience']=array('name'=>'techexperience',	'friendlyname'=>'Tech Experience', 		'type'=>'text',		'length'=>255,		'flags'=>array("required"));
		$this->sd['telno']=			array('name'=>'telno',			'friendlyname'=>'Telephone Number', 	'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['guardiantelno']=	array('name'=>'guardiantelno',	'friendlyname'=>'Guardian Tel.', 		'type'=>'text',		'length'=>45,		'flags'=>array());
		$this->sd['guardianname']=	array('name'=>'guardianname',	'friendlyname'=>'Guardian Name', 		'type'=>'text',		'length'=>45,		'flags'=>array());
		$this->sd['guardianemail']=	array('name'=>'guardianemail',	'friendlyname'=>'Guardian Email', 		'type'=>'email',	'length'=>45,		'flags'=>array());
		$this->sd['guardianconfirm']=array('name'=>'guardianconfirm','friendlyname'=>'Guardian Confirmation','type'=>'int',		'length'=>1,		'flags'=>array());
		$this->sd['twitter']=		array('name'=>'twitter',		'friendlyname'=>'Twitter Name', 		'type'=>'twitter',	'length'=>45,		'flags'=>array());
		//$this->sd['centre']=		array('name'=>'centre',			'friendlyname'=>'', 					'type'=>'int',		'length'=>10,		'flags'=>array("fk"),					'fk'=>array('centres.id'));
		$this->sd['id']=			array('name'=>'id',				'friendlyname'=>'ID', 					'type'=>'int',		'length'=>45,		'flags'=>array("id","hidden"));
		
		$this->tablename="people";
		
		if(isset($id)&&$id!=0){
			$this->loadFromDB($id);
		}
	}
}

class Centre extends Entity {
	
	function __construct($id=0){
		//		   VAR NAME HERE	FIELD IN DB
		$this->sd['city']=			array('name'=>'city',			'friendlyname'=>'City', 				'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['latlng']=		array('name'=>'latlng',			'friendlyname'=>'Geography',			'type'=>'latlng',	'length'=>45,		'flags'=>array("required"));
		$this->sd['name']=			array('name'=>'name',			'friendlyname'=>'Name',					'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['description']=	array('name'=>'description',	'friendlyname'=>'Description',			'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['address']=		array('name'=>'address',		'friendlyname'=>'Address', 				'type'=>'text',		'length'=>255,		'flags'=>array("required"));
		$this->sd['notes']=			array('name'=>'notes',			'friendlyname'=>'Notes', 				'type'=>'text',		'length'=>45,		'flags'=>array());
		$this->sd['referrer']=		array('name'=>'referrer',		'friendlyname'=>'How You Found Us', 	'type'=>'text',		'length'=>45,		'flags'=>array());
		$this->sd['contactmentor']=	array('name'=>'contactmentor',	'friendlyname'=>'Contact Mentor', 		'type'=>'int',		'length'=>1000,		'flags'=>array("fk","required"),		'fk'=>array('mentors.email'));
		$this->sd['contacttel']=	array('name'=>'contacttel',		'friendlyname'=>'Contact Telephone',	'type'=>'tel',		'length'=>45,		'flags'=>array("required"));
		$this->sd['id']=			array('name'=>'id',				'friendlyname'=>'ID', 					'type'=>'int',		'length'=>255,		'flags'=>array("id","hidden"));
		$this->sd['status']=		array('name'=>'status',			'friendlyname'=>'Status', 				'type'=>'bool',		'length'=>1,		'flags'=>array());
		$this->tablename="centres";
		
		if(isset($id)&&$id!=0){
			$this->loadFromDB($id);
		}
	}
}

class Mentor extends Entity {
	
	function __construct($id=0){
		//		   VAR NAME HERE	FIELD IN DB
		$this->sd['city']=			array('name'=>'city',			'friendlyname'=>'City', 				'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['email']=			array('name'=>'email',			'friendlyname'=>'Email Address', 		'type'=>'email',	'length'=>45,		'flags'=>array("required"));
		$this->sd['latlng']=		array('name'=>'latlng',			'friendlyname'=>'Geography', 			'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['name']=			array('name'=>'name',			'friendlyname'=>'Full Name', 			'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['notes']=			array('name'=>'notes',			'friendlyname'=>'Notes', 				'type'=>'text',		'length'=>45,		'flags'=>array());
		$this->sd['referrer']=		array('name'=>'referrer',		'friendlyname'=>'How You Found Us', 	'type'=>'text',		'length'=>45,		'flags'=>array());
		$this->sd['geekcred']=		array('name'=>'geekcred',		'friendlyname'=>'Geeky Credentials',	'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['telno']=			array('name'=>'telno',			'friendlyname'=>'Telephone Number',		'type'=>'text',		'length'=>45,		'flags'=>array("required"));
		$this->sd['twitter']=		array('name'=>'twitter',		'friendlyname'=>'Twitter', 				'type'=>'text',		'length'=>45,		'flags'=>array());
		$this->sd['id']=			array('name'=>'id',				'friendlyname'=>'ID', 					'type'=>'int',		'length'=>45,		'flags'=>array("id","hidden"));
		
		$this->tablename="mentors";
		
		if(isset($id)&&$id!=0){
			$this->loadFromDB($id);
		}
	}
}


// /// //// ///// ////// /////// //////// ////////// //////////////// ///////////////////////////

class FormException extends Exception{
	
}

// /// //// ///// ////// /////// //////// ////////// //////////////// ///////////////////////////

$db=new Database("localhost","root","","yrs");
//$db->debug=true;

