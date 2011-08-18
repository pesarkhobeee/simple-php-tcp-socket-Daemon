#!/usr/bin/php -q
<?php
/**
 * @name simple php tcp socket Daemon
 * @Description
 * this is an example just for show that php can handle tcp request 
 * Before run this example you shod install System_Daemon whith Pear
 * After run this php file insert below code on your shell :
 * curl -d "name=Pejman"  127.0.0.1:9001
 * At the end of this experiment for stop this daemon you mast find phptcp directory in /var/run 
 * and show pid of this daemon and kill it or just use killall -9 php_tcp_daemon.php!
*/


/**
 * System_Daemon turns PHP-CLI scripts into daemons.
 * 
 * PHP version 5
 *
 * @category  System
 * @package   System_Daemon
 * @author    Kevin <kevin@vanzonneveld.net>
 * @copyright 2008 Kevin van Zonneveld
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: simple.php 276201 2009-02-20 16:55:07Z kvz $
 * @link      http://trac.plutonia.nl/projects/system_daemon
 */



// This is for PEAR developers only
ini_set('include_path', ini_get('include_path').':..');

// Include Class
error_reporting(E_ALL);
require_once "System/Daemon.php";

// Bare minimum setup
System_Daemon::setOption("appName", "phptcp");
System_Daemon::setOption("authorEmail", "pesarkhobeee@gmail.com");

//System_Daemon::setOption("appDir", dirname(__FILE__));
System_Daemon::log(System_Daemon::LOG_INFO, "Daemon not yet started so ".
    "this will be written on-screen");

// Spawn Deamon!
System_Daemon::start();
System_Daemon::log(System_Daemon::LOG_INFO, "Daemon: '".
    System_Daemon::getOption("appName").
    "' spawned! This will be written to ".
    System_Daemon::getOption("logLocation"));

// Your normal PHP code goes here. Only the code will run in the background
// so you can close your terminal session, and the application will
// still run.

// Set time limit to indefinite execution
set_time_limit (0);

// Set the ip and port we will listen on
$address = '127.0.0.1';
$port = 9001;

// Create a TCP Stream socket
$sock = socket_create(AF_INET, SOCK_STREAM, 0);
// Bind the socket to an address/port
socket_bind($sock, $address, $port) or die('Could not bind to address');
// Start listening for connections
socket_listen($sock);


while(1){
/* Accept incoming requests and handle them as child processes */
$client = socket_accept($sock);

// Read the input from the client &#8211; 1024 bytes
$input = socket_read($client, 1024);


$output = ereg_replace(".*name=","",$input).chr(0);

// Display output back to client
socket_write($client,"Hi ".$output.". This is php ;) enjoy with it!");

}



// Close the client (child) socket
socket_close($client);



System_Daemon::stop();
?>
