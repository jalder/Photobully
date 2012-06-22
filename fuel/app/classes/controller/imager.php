<?php
/**
 * Imager manages the core of the app functionality.
 * 
 * @author John Alder http://jalder.com
 *
 */

class Controller_Imager extends Controller{
	
	private $theme = 'default';
	private $user_id = 0;
	
	public function before(){
		$auth = Auth::get_user_id();
		$this->user_id = $auth[1];
		if(!$this->user_id){
			//not logged in, do something about it
			die('Not Logged In');
		}
	}
	
	public function action_index(){
		$view = View::forge($this->theme.'/index');
		$view->images = Model_Image::find()->order_by('created_at','DESC')->limit(9)->get(); //get group of images for the recent View area
		return $view->render();
	}
	
	public function action_gallery(){
		$view = View::forge($this->theme.'/gallery');
		$images = Model_Image::find()->limit(20)->get();
		$view->images = $images;
		return $view;
	}
	
	public function action_get(){
		$id = self::alphaID($this->param('id'),true);
		$image = Model_Image::find($id);
		$path = APPPATH.'files/'.$image->short_name;
		$config = array();
		$img = Image::load($path);
		$img->output();
		die();
	}
	
	public function action_upload(){
		$message = array();
		$config = array(
		    'path' => APPPATH.'files',
		    'randomize' => true,
		    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
		);
		
		if(Input::post('upload')){
			Upload::process($config);
			if(Upload::is_valid()){
				$files = Upload::get_files();
				var_dump($files);
				foreach($files as $f){
					$prop = array(
						'short_name'=>'',
						'original_name'=>$f['name'],
						'file_type'=>$f['mimetype'],
						'ext'=>$f['extension']
					);
					$short_name = self::store_image($f['file'],$prop);
					$message[] = array(
						'short_name'=>$short_name,
						'original_name'=>$f['name']
					);
					
				}
			}
		}
		return $this->response->body(Format::forge($message)->to_json());
	}
	
	public function action_webget(){
		$message = array();
		if(Input::post('webget')){
			$webget = trim(Input::post('webget'));
			$list = explode("\n",$webget);
			foreach($list as $url){
				$url = trim($url);
				//Validate Image URL 
				$imagesize = getimagesize($url);
				if(is_array($imagesize)){
					//var_dump($imagesize);
					//Get Image
					$prop = array();
					$ch = curl_init($url);
					$tmp = APPPATH.'tmp/'.uniqid();
					$fp = fopen($tmp, 'wb');
					curl_setopt($ch, CURLOPT_FILE, $fp);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_exec($ch);
					curl_close($ch);
					fclose($fp);
					
					$prop['original_name'] = $url;
					$prop['file_type'] = $imagesize['mime'];
					//Validate Image
					self::validate_image($tmp);
					//Store Image
					if($short_name = self::store_image($tmp, $prop)){
						$message[] = array(
							'short_name'=>$short_name,
							'original_name'=>$prop['original_name']
						);
					}
					//Add Meta/Group Details to File Object to Image Model
					//Append JSON message
				}
			}
			//End Loop
		}
		return $this->response->body(Format::forge($message)->to_json());
	}
	
	private function validate_image($loc){
		//Check Allowed Types
		//Check Size
	}
	
	private function store_image($loc,$prop = array()){
		$image = new Model_Image();
		$image->short_name = '';
		$image->original_name = $prop['original_name'];
		$image->user_id = $this->user_id;
		$image->file_type = $prop['file_type'];
		$image->save();
		//var_dump($prop['file_type']);
		//die();
		switch($prop['file_type']){
			case 'image/jpeg':
				$ext = '.jpeg';
				break;
			case 'image/png':
				$ext = '.png';
				break;
			default:
				$ext = '.jpeg';
				break;
		}
		if(isset($prop['ext'])){
			$ext = '.'.$prop['ext'];
		}
		$image->short_name = self::alphaID($image->get_id(),false).$ext;
		if(rename($loc,APPPATH.'files/'.$image->short_name)){
			$image->save();
		}
		return $image->short_name;
	}
	
	//alphaID function courtesy of 
	//http://kevin.vanzonneveld.net/techblog/article/create_short_ids_with_php_like_youtube_or_tinyurl/
	private function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
	{
	    $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    if ($passKey !== null)
	    {
	        /* Although this function's purpose is to just make the
	        * ID short - and not so much secure,
	        * with this patch by Simon Franz (http://blog.snaky.org/)
	        * you can optionally supply a password to make it harder
	        * to calculate the corresponding numeric ID */
	
	        for ($n = 0; $n<strlen($index); $n++)
	        {
	            $i[] = substr( $index,$n ,1);
	        }
	
	        $passhash = hash('sha256',$passKey);
	
	        $passhash = (strlen($passhash) < strlen($index)) ? hash('sha512',$passKey) : $passhash;
	
	        for ($n=0; $n < strlen($index); $n++)
	        {
	            $p[] =  substr($passhash, $n ,1);
	        }
	
	        array_multisort($p,  SORT_DESC, $i);
	        $index = implode($i);
	    }
	
	    $base  = strlen($index);
	
	    if ($to_num)
	    {
	        // Digital number  <<--  alphabet letter code
	        $in  = strrev($in);
	        $out = 0;
	        $len = strlen($in) - 1;
	
	        for ($t = 0; $t <= $len; $t++)
	        {
	            $bcpow = bcpow($base, $len - $t);
	            $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
	        }
	
	        if (is_numeric($pad_up))
	        {
	            $pad_up--;
	            if ($pad_up > 0)
	            {
	                $out -= pow($base, $pad_up);
	            }
	        }
	        $out = sprintf('%F', $out);
	        $out = substr($out, 0, strpos($out, '.'));
	    }
	    else
	    {
	        // Digital number  -->>  alphabet letter code
	        if (is_numeric($pad_up))
	        {
	            $pad_up--;
	            if ($pad_up > 0)
	            {
	                $in += pow($base, $pad_up);
	            }
	        }
	
	        $out = "";
	        for ($t = floor(log($in, $base)); $t >= 0; $t--)
	        {
	            $bcp = bcpow($base, $t);
	            $a   = floor($in / $bcp) % $base;
	            $out = $out . substr($index, $a, 1);
	            $in  = $in - ($a * $bcp);
	        }
	        $out = strrev($out); // reverse
	    }
	    return $out;
	}
}