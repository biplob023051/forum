<?php
/**
 * This is website custom configuration file.
 */
 
/**
 *	Define Site Language
 */
Configure::write('Config.language', 'eng');

/**
 *	Site parameters
 */
Configure::write('Site.Name','Diwali');
Configure::write('Site.Copyright', 'Copyright &copy; 2015 Diwali. All rights reserved.');

/* configure error notification email */
Configure::write('Site.ErrorNotifyEmail',array('biplob.weblancer@gmail.com'));

/* loads all plugins at once */
CakePlugin::loadAll();

/* Set to an array of prefixes we want to use in our application. */ 	

Configure::write('Routing.prefixes', array('backoffice', 'client'));

// SBPS決済情報
define("SBPS_MERCHANT_ID", "68442");
define("SBPS_SERVICE_ID", "001");