<?php
class User {
	
	function View() {
		$users = array();
		$users[0] = array('id', 'Niroshan', '25');
		$users[1] = array('id', 'Kanishka', '26');
		$users[2] = array('id', 'Amila', '25');
		
		echo json_encode($users);
	}
}
?>