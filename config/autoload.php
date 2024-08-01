<?php
//depois vou passar o autoload para um arquivo externo
spl_autoload_register(function ($class_name) {
    if(file_exists('model/'.$class_name . '.php')){
		require_once('model/'.$class_name . '.php');
	}elseif(file_exists('model/utils/'.$class_name . '.php')){
		require_once('model/utils/'.$class_name . '.php');
	}
});
