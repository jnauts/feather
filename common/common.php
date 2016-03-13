<?php

function show_404() {
	ob_end_clean(); // clean the oyutput buffer if some output is exist already
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
	include("../views/template/show_404.html");
}

?>