<?php 



class Controller_Album extends Controller{
	
	private $theme = 'default';
	
	public function before(){
		
		
		
	}
	
	
	public function action_index(){
		$id = $this->param('id');
		
		$album = Model_Album::find($id);
		if($album){
			
			$view = View::forge($this->theme.'/album');
			$view->header = View::forge($this->theme.'/includes/header',array('active'=>'gallery'));
			$view->footer = View::forge($this->theme.'/includes/footer');
			$view->album = $album;
			$view->username = Model_User::find($album->user_id)->get_username();
			$view->images = $album->images;

			return $view;
			
		}
		
	}
	
}