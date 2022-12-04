<?php
/*
 function ObtenerNavegador($user_agent) {  
      $navegadores = array(  
           'Zafari' => 'Zafari',
		   'Ox	x	Opera' => 'Opera',  
           'Mozilla Firefox'=> '(Firebird)|(Firefox)',  
           'Galeon' => 'Galeon',  
           'Mozilla'=>'Gecko', 
		   'Chrome'=>'Chrome', 
           'MyIE'=>'MyIE',  
           'Lynx' => 'Lynx',  
           'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)',  
           'Konqueror'=>'Konqueror',  
           'Internet Explorer 9' => '(MSIE 9\.[0-9]+)',
		   'Internet Explorer 8' => '(MSIE 8\.[0-9]+)',
		   'Internet Explorer 7' => '(MSIE 7\.[0-9]+)',  
           'Internet Explorer 6' => '(MSIE 6\.[0-9]+)',  
           'Internet Explorer 5' => '(MSIE 5\.[0-9]+)',  
           'Internet Explorer 4' => '(MSIE 4\.[0-9]+)',
		   'Internet Explorer 3' => '(MSIE 3\.[0-9]+)', 
		   'Internet Explorer 2' => '(MSIE 2\.[0-9]+)',
		   'Internet Explorer 1' => '(MSIE 1\.[0-9]+)',
		   'Internet Explorer 0' => '(MSIE 0\.[0-9]+)',  
 );  
 foreach($navegadores as $navegador=>$pattern){  
        if (eregi($pattern, $user_agent))  
        return $navegador;  
     }  
 return 'Desconocido';  
 } */
 $nav=$_SERVER['HTTP_USER_AGENT'];
 ?>