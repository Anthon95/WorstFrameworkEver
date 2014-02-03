<?php

	/* bootstrap class */
	class WFEBootstrap {

		/* launching the app */
		public static function launcher(){

			/* config */
            require_once("./config/config.php");

            /* default values */
            $action = $_GET['action'];

			/* ROUTING */
            if(isset($action))
            {
                /* we check if the route is correct */
                if(file_exists("./WFE".$action.".php")){

                    /* we require it */
                    require_once("./WFE".$action.".php");
                }
            }

			/* we kill */
			die;

		}

	}