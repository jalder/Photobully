<?php 

class Controller_Rackspace extends Controller{
	
	private $username = '';
	private $api_key = '';
	private $conn;
	private $user_id = 0;
	
	public function before(){
		
		$user = Auth::get_user_id();
		$this->user_id = $user[1];
		
		$rack = Model_Rackspace_Cdn::find()->where('user_id',$this->user_id)->get_one();
		if(!$rack){
			die('no rackspace credentials stored');
		}
		else{
			$this->username = Crypt::decode($rack->username);
			$this->api_key = Crypt::decode($rack->api_key);
		}
		
		require_once(APPPATH.'vendor/rackspace_api/cloudfiles.php');
		$auth = new CF_Authentication($this->username, $this->api_key);
		$auth->authenticate();
		
		$this->conn = new CF_Connection($auth);

	}
	
	public function action_index(){
		
	}
	
	public function create_container($name){
		
		return $this->conn->create_container($name);
	}
	
	public function store_object($file,$container){
		try{
			$con = $this->conn->get_container($container);
		} catch (Exception $e){
			$con = self::create_container($container);			
		}

		
		$object = $con->create_object($file);
		
		$object->write(File::read(APPPATH.'files/'.$file,true),File::get_size(APPPATH.'files/'.$file));
		$url = $con->make_public();

		return $url;
	}
	
	public function get_containers(){
		
		return $files = $this->conn->get_containers();
	}
}