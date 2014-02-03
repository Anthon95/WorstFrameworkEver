<?php

	/* we start our sessions */
	session_start();

    /* we include our libs */
    require_once("./libs/smarty/Smarty.class.php");

	/* autoload */
	require_once("./core/WFEAutoload.php");

    /* launching our autoload */
    new WFEAutoload();

	/* launching our bootstrap */
	WFEBootstrap::launcher();