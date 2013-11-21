<?
#add these lines to your httpd conf to use mod_rewrite to allow
#urls like http://url.fibiger.org/XXXXX to work correctly
#   RewriteEngine on
#   RewriteRule ^/$ /add.php
#   RewriteRule ^/(\w*)$  /index.php?x=$1

$shorturl = $_REQUEST["x"];
include 'url-map.php';

if( $map[$shorturl]['u'] && ( $map[$shorturl]['c']==='-1' || (int)$map[$shorturl]['c']>=0 ) ) {
	if( (int)$map[$shorturl]['c']>=0 ) {
		$lines = file('url-map.php');
		foreach ($lines as $individual) {
			if( preg_match( "/\'".$shorturl."\'/", $individual ) ) {
				$cc = (int)$map[$shorturl]['c']-1;
				if( $cc > 0 ) {
					$save .= "'" . $shorturl . "' => array( 'u'=> '" .$map[$shorturl]['u']. "', 'c'=>'" .$cc. "' ),  \n";
				}
			}else{
				$save .= $individual;
			}
		}
		$handle = fopen('url-map.php', 'wb+');
		fwrite($handle, $save);
		fclose($handle);
	}
	header( 'Location: '.$map[$shorturl]['u'] );
} else {
	?>
	<HTML>
	<HEAD>
	<TITLE>url redirector</TITLE>
	<link href="url.css" rel="stylesheet" type="text/css">
	</HEAD>
	<BODY>
	<br><br><br><br><br><br><br>
	<div id="content">
	<?
	print ("I'm sorry, that url does not exist. please check the value<br>and try again.");
	print ("<br><br>or <a href='add.php'>add</a> a new shortened url");
}

?>
</div>
</BODY>
</HTML>
