<?php

	/* autoload class */
	class WFEAutoload {
		
		/* launcher */
		public function __construct(){

			/* register */
			spl_autoload_register(array($this,"autoload"));

		}

		/* autoload */
		private function autoload($class){

			/* we check if the file exists */
            if(file_exists("./core/".$class.".php")){

                /* if it does we require it */
                require_once("./core/".$class.".php");
            }

		}

	}