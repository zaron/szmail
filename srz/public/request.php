<?php
/**
 * szMail
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with
 * this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://szlab.de/license/new-bsd
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@szlab.de so
 * we can send you a copy immediately.
 *
 *
 * @copyright  Copyright (c) 2010 - $currentYear$ Otto von Wesendonk, Eric Barmeyer (http://szlab.de)
 * @version    $Id$
 * @license    http://szlab.de/license/new-bsd     New BSD License
 */

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
realpath(APPLICATION_PATH . '/../library'),
get_include_path(),
)));

//set limits
set_time_limit(0);
ini_set('memory_limit', -1);

//set encoding
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
mb_internal_encoding('UTF-8');

//time zone
date_default_timezone_set('Europe/Berlin');

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
APPLICATION_ENV,
APPLICATION_PATH . '/configs/application.ini'
);

error_reporting(E_ALL);

$application->bootstrap()
->run();


