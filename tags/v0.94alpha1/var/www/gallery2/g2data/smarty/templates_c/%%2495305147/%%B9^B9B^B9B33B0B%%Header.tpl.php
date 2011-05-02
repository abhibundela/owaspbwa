<?php /* Smarty version 2.6.10, created on 2011-04-20 13:26:14
         compiled from gallery:modules/slideshow/templates/Header.tpl */ ?>
<script type="text/JavaScript">
    //<![CDATA[
    var agent = navigator.userAgent.toLowerCase();
    var appver = parseInt(navigator.appVersion);
    var bCanBlend = (agent.indexOf('msie') != -1) && (agent.indexOf('opera') == -1) &&
                    (appver >= 4) && (agent.indexOf('msie 4') == -1) &&
                    (agent.indexOf('msie 5.0') == -1);
    var filterNames = new Array(16), filters = new Array(16);
    filterNames[0] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Blend','forJavascript' => '1'), $this);?>
';
    filters[0] = 'progid:DXImageTransform.Microsoft.Fade(duration=1)';
    filterNames[1] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Blinds','forJavascript' => '1'), $this);?>
';
    filters[1] = 'progid:DXImageTransform.Microsoft.Blinds(duration=1,bands=20)';
    filterNames[2] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Checkerboard','forJavascript' => '1'), $this);?>
';
    filters[2] = 'progid:DXImageTransform.Microsoft.Checkerboard(duration=1,squaresX=20,squaresY=20)';
    filterNames[3] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Diagonal','forJavascript' => '1'), $this);?>
';
    filters[3] = 'progid:DXImageTransform.Microsoft.Strips(duration=1,motion=rightdown)';
    filterNames[4] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Doors','forJavascript' => '1'), $this);?>
';
    filters[4] = 'progid:DXImageTransform.Microsoft.Barn(duration=1,orientation=vertical)';
    filterNames[5] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Gradient','forJavascript' => '1'), $this);?>
';
    filters[5] = 'progid:DXImageTransform.Microsoft.GradientWipe(duration=1)';
    filterNames[6] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Iris','forJavascript' => '1'), $this);?>
';
    filters[6] = 'progid:DXImageTransform.Microsoft.Iris(duration=1,motion=out)';
    filterNames[7] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Pinwheel','forJavascript' => '1'), $this);?>
';
    filters[7] = 'progid:DXImageTransform.Microsoft.Wheel(duration=1,spokes=12)';
    filterNames[8] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Pixelate','forJavascript' => '1'), $this);?>
';
    filters[8] = 'progid:DXImageTransform.Microsoft.Pixelate(duration=1,maxSquare=10)';
    filterNames[9] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Radial','forJavascript' => '1'), $this);?>
';
    filters[9] = 'progid:DXImageTransform.Microsoft.RadialWipe(duration=1,wipeStyle=clock)';
    filterNames[10] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Rain','forJavascript' => '1'), $this);?>
';
    filters[10] = 'progid:DXImageTransform.Microsoft.RandomBars(duration=1,orientation=vertical)';
    filterNames[11] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Slide','forJavascript' => '1'), $this);?>
';
    filters[11] = 'progid:DXImageTransform.Microsoft.Slide(duration=1,slideStyle=push)';
    filterNames[12] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Snow','forJavascript' => '1'), $this);?>
';
    filters[12] = 'progid:DXImageTransform.Microsoft.RandomDissolve(duration=1,orientation=vertical)';
    filterNames[13] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Spiral','forJavascript' => '1'), $this);?>
';
    filters[13] = 'progid:DXImageTransform.Microsoft.Spiral(duration=1,gridSizeX=40,gridSizeY=40)';
    filterNames[14] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Stretch','forJavascript' => '1'), $this);?>
';
    filters[14] = 'progid:DXImageTransform.Microsoft.Stretch(duration=1,stretchStyle=push)';
    filterNames[15] = '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'RANDOM','forJavascript' => '1'), $this);?>
';
    filters[15] = 'RANDOM';
    // ]]>
</script>