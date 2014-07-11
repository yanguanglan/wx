<?php
$mu = Session::get('mu');
if('reldel'==Request::get(1)){
	$ids = explode(',', Request::post('id'));	
	foreach ($ids as $id){
		$user = new Model('pubs');
		$user->find($id);
	    $user->remove();		
	}
	Response::write('ok');
}
Response::write('no');
