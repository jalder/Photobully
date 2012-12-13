<?php 

//http://piwik.jalder.com/?module=API&idSite=11&period=week&date=2012-07-19&token_auth=e51970deb2dc7ae8f1c867fd03973b5e&downloadUrl=http://img.jalder.com/g/ns.jpeg&format=json&method=Actions.getDownload


namespace Piwik; 

class Controller_Piwik{
	
	public $idSite = 11;
	
	private $url = 'http://piwik.jalder.com/';
	
	private $method = '';
	
	private $period = 'week';
	private $date = '2012-07-19';
	private $token_auth = 'e51970deb2dc7ae8f1c867fd03973b5e';
	private $downloadUrl = 'http://img.jalder.com/g/ns.jpeg';
	private $format = 'json';
	
	
	
	public function getDownload($downloadUrl){
		
		$this->method = 'Actions.getDownload';
		$this->downloadUrl = $downloadUrl;
		
		$query = $this->url.'?module=API';
		$query .= '&idSite='.$this->idSite;
		$query .= '&period='.$this->period;
		$query .= '&date='.$this->date;
		$query .= '&token_auth='.$this->token_auth;
		$query .= '&downloadUrl='.$this->downloadUrl;
		$query .= '&format='.$this->format;
		$query .= '&method='.$this->method;
		
		$json = file_get_contents($query);
		$data = json_decode($json);
		
		if(isset($data[0])){
			return $data[0];
		}
		else{
			return false;
		}
		
	}
	
	
	
	
	
	
}