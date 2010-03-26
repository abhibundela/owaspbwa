<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT.'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'phpids' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ] .= $page[ 'title_separator' ].'Setup';
$page[ 'page_id' ] = 'setup';

if( isset( $_POST[ 'create_db' ] ) ) {
	if( !@mysql_connect( $_DVWA[ 'db_server' ], $_DVWA[ 'db_user' ], $_DVWA[ 'db_password' ] ) ) {
		dvwaMessagePush( "Could not connect to the database - please check the config file." );
		dvwaPageReload();
	}

	// Create database
	$drop_db = "DROP DATABASE IF EXISTS dvwa;";
	if( !@mysql_query ( $drop_db ) ) {
		dvwaMessagePush( "Could not drop existing database<br />SQL: ".mysql_error() );
		dvwaPageReload();
	}

	$create_db = "CREATE DATABASE dvwa;";

	if( !@mysql_query ( $create_db ) ) {
		dvwaMessagePush( "Could not create database<br />SQL: ".mysql_error() );
		dvwaPageReload();
	}

	dvwaMessagePush( "Database has been created." );

	// Create table 'users'
	if( !@mysql_select_db( $_DVWA[ 'db_database' ] ) ) {
		dvwaMessagePush( 'Could not connect to database.' );
		dvwaPageReload();
	}

	$create_tb = "CREATE TABLE users (user_id int(6),first_name varchar(15),last_name varchar(15), user varchar(15), password varchar(32),avatar varchar(70), PRIMARY KEY (user_id));";
	if( !mysql_query( $create_tb ) ){
		dvwaMessagePush( "Table could not be created<br />SQL: ".mysql_error() );
		dvwaPageReload();
	}

	dvwaMessagePush( "'users' table was created." );

	// Insert some data into users

	// Get the base directory for the avatar media...
	$baseUrl = 'http://'.$_SERVER[ 'SERVER_NAME' ].$_SERVER[ 'PHP_SELF' ];
	$stripPos = strpos( $baseUrl, 'dvwa/setup.php' );
	$baseUrl = substr( $baseUrl, 0, $stripPos ).'dvwa/hackable/users/';

	$insert = "INSERT INTO users VALUES
		('1','admin','admin','admin',MD5('admin'),'{$baseUrl}admin.jpg'),
		('2','Gordon','Brown','gordonb',MD5('abc123'),'{$baseUrl}gordonb.jpg'),
		('3','Hack','Me','1337',MD5('charley'),'{$baseUrl}1337.jpg'),
		('4','Pablo','Picasso','pablo',MD5('letmein'),'{$baseUrl}pablo.jpg'),
		('5','bob','smith','user',MD5('user'),'{$baseUrl}smithy.jpg');";
	if( !mysql_query( $insert ) ){
		dvwaMessagePush( "Data could not be inserted into 'users' table<br />SQL: ".mysql_error() );
		dvwaPageReload();
	}
	dvwaMessagePush( "Data inserted into 'users' table." );
	
	// Create guestbook table
	$create_tb_guestbook = "CREATE TABLE guestbook (comment_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, comment varchar(32), name varchar(20), PRIMARY KEY (comment_id));";
	
	if( !mysql_query( $create_tb_guestbook ) ){
		dvwaMessagePush( "Table could not be created<br />SQL: ".mysql_error() );
		dvwaPageReload();
	}
	
	dvwaMessagePush( "'guestbook' table was created." );
	
	// Insert data into 'guestbook'
	$insert = "INSERT INTO guestbook VALUES
	('1','This is a test comment.','test');";
	
	if( !mysql_query( $insert ) ){
		dvwaMessagePush( "Data could not be inserted into 'guestbook' table<br />SQL: ".mysql_error() );
		dvwaPageReload();
	}
	dvwaMessagePush( "Data inserted into 'guestbook' table." );

	dvwaMessagePush( "Setup successful!" );
	dvwaPageReload();
}


$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Database setup <img src=\"".DVWA_WEB_PAGE_TO_ROOT."dvwa/images/spanner.png\"></h1>

	<p>Click on the 'Create / Reset Database' button below to create or reset your database. If you get an error make sure you have the correct user credentials in /config/config.inc.php</p>

	<p>If the database already exists, it will be cleared and the data will be reset.</p>

	<br />
	
	<!-- Create db button -->
	<form action=\"#\" method=\"post\">
		<input name=\"create_db\" type=\"submit\" value=\"Create / Reset Database\">
	</form>
</div>
";


dvwaHtmlEcho( $page );

?>
