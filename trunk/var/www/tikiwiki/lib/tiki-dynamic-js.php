<?php
$list = array();
if( is_array( $_SESSION['tiki_cookie_jar'] ) )
	foreach( $_SESSION['tiki_cookie_jar'] as $name=>$value )
		$list[] = $name . ": '" . addslashes($value) . "'";
?>
<script type="text/javascript">
var tiki_cookie_jar = new Array();
tiki_cookie_jar = {
	<?php echo implode( ",\n\t", $list ) ?>
};
</script>
