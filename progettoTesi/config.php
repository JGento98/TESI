<?php
session_start();

// connect to database
$conn = mysqli_connect("localhost", "root", "", "scrapedb");

if (!$conn) {
    die("Error connecting to database: " . mysqli_connect_error());
}

define ('ROOT_PATH', realpath(dirname(__FILE__)));
/*      ROOT_PATH is set to the physical address with respect to the operating system, 
        to the current directory on which this file (config.php) resides. On my machine for example,
        ROOT_PATH has the value /opt/lampp/htdocs/complete-blog-php/. It is used to include physical files like PHP source code files 
        (like the ones we just included), physical downloadable files like images, video files, audio files, etc. But in this tutorial, 
        we will use it only to include PHP source files.
 */ 

define('BASE_URL', 'http://localhost/progettoTesi/');
/*  BASE_URL is merely a web address that sets a URL pointing to the root of our website. 
    In our case, its value is http://localhost/progettoTesi. 
    It is used to form links to CSS, images, javascript.
*/
?>