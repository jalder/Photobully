<?php 


class Controller_Account extends Controller{
	
	private $user_id = 0;
	
	public function before(){
		$user = Auth::get_user_id();
		$this->user_id = $user[1]; 
		
	}
	
	public function action_login(){
		if(Auth::check()){
			Response::redirect('/');
			die();
		}
		if(Input::post()){
			$auth = Auth::instance();
			if($auth->login()){
				Response::redirect('/');
				die();
			}
		}
		else{
			//die('could not log in.');
			$view = View::forge('default/includes/login.form');
			$view->header = View::forge('default/includes/header',array('active'=>'login'));
			$view->footer = View::forge('default/includes/footer');
			$view->active = 'login';
			return $view;
		}
		
		
	}
	
	public function action_logout(){
		$auth = Auth::instance();
		$auth->logout();
		Response::redirect('/account/login');
		
	}
	
	public function action_settings(){
		$msg = '';
		if($this->user_id==0){
			die('You have no business being here.  Please log in.');
		}
		
		$user = Model_User::find($this->user_id);
		$auth = Auth::instance();
		
		if(Input::post('rackspace_user')){
			$rack = Model_Rackspace_Cdn::find()->where('user_id',$this->user_id)->get_one();
			
			if($rack->id){
				
				$rack->api_key = Crypt::encode(Input::post('rackspace_api_key'));
				$rack->username = Crypt::encode(Input::post('rackspace_user'));
				$rack->save(); 
			}
			else{
				$rack = new Model_Rackspace_Cdn();
				$rack->user_id = $this->user_id;
				$rack->api_key = Crypt::encode(Input::post('rackspace_api_key'));
				$rack->username = Crypt::encode(Input::post('rackspace_user'));
				$rack->save();
			}
		}
		
		if(Input::post('email')){
			$user->email = Input::post('email');
			$user->save();
		}
		
		if(Input::post('c_password')){
			if(Input::post('c_password')==Input::post('r_password')){
				if($auth->change_password(Input::post('o_password'),Input::post('c_password'))){
					$msg = 'Password change successful';
				}
				else{
					$msg = 'Failed to change password';
				}
				
			}
			
		}
		
		$view = View::forge('default/settings');
		$view->albums = Model_Album::find()->where('user_id',$this->user_id)->get();
		$view->header = View::forge('default/includes/header',array('active'=>'settings'));
		$view->footer = View::forge('default/includes/footer');
		$rackspace = Model_Rackspace_Cdn::find()->where('user_id',$this->user_id)->get_one();
		if($rackspace->id){
			$view->rackspace_username = Crypt::decode($rackspace->username);
			$view->rackspace_api_key = Crypt::decode($rackspace->api_key);
		}
		else{
			$view->rackspace_username = $view->rackspace_api_key = '';
		}
		
		$view->user = $user;
		$view->msg = $msg;
		$view->active = 'settings';
		return $view;
	}
	
	public function action_genkey(){
		$msg = array();
		
		if($this->user_id){
			$new_key = sha1(mt_rand(10000,99999).time().$this->user_id);
			$user = Model_User::find($this->user_id);
			$user->api_key = $new_key;
			$user->save();
			$msg['success'] = 'success';
			$msg['api_key'] = $new_key;
		}
		else{
			$msg['error'] = 'insufficient permissions';
		}
		return Format::forge($msg)->to_json();
	}
	
}