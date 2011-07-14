<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>
<?php if( empty($product_types)) return; 
/**
	 * Available indexes:
	 * 
	 * $product_type_params["parameter_label"] => The label for each product type parameter
	 * $product_type_params["parameter_description"] => The description of each product type parameter
	 * $product_type_params["tooltip"] => Same as above but wrapped in a tooltip
	 * $product_type_params["parameter_value"] => The actual value of the parameter for this product
	 * $product_type_params["parameter_unit"] => The unit of the parameter
	 * $product_type["product_type_name"] => The name of the product type
	 * 
	 */
?>
<!-- Tables of product_types -->

<?php 
foreach( $product_types as $product_type ) { // Loop through all recent products
	foreach( $product_type as $attr => $val ) {
    	//echo $attr." - ".$val."<br />";
        $this->set( $attr, $val );
        
    }
    
    ?><br /><table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr><td colspan="2"><strong><?php 
    echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_PARAMETERS_IN_CATEGORY').": ".$product_type["product_type_name"];
    ?></strong></td></tr><?php 
    $i = 0;
	if ( !empty( $product_type["parameters"] ) ) {
		foreach( $product_type["parameters"] as $product_type_params ) {
    		foreach( $product_type_params as $attr => $val ) {
    			$this->set( $attr, $val );
    		}
			if ( $i++ % 2 ) {
    			$bgcolor = 'row0';
			}
			else {
    			$bgcolor = 'row1';
			}
			if ( $i > $product_type["product_type_count_params"] ) {
				break;
			}

			?><tr class="<?php echo $bgcolor;?> height="18">
			<td width="30%"><?php echo $product_type_params["parameter_label"]; 

			if ( !empty($product_type_params["tooltip"] ) ) { ?>
    			&nbsp;<?php echo $product_type_params["tooltip"]; 
			} 
			?>
			</td><td><?php echo $product_type_params["parameter_value"];

			if ( !empty($product_type_params["parameter_unit"] ) ) {
				echo " ".$product_type_params["parameter_unit"];
			} ?>
			</td></tr>
			<?php
		}
	}
	?>
    </table><?php 
}
?>
