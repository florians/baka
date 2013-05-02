<?php  
 /**
 * @Author Florian Stettler, Adrian Locher
 * @Version 9
 * Create Date:   19.03.2013  creation of the file
 * 
 * This is the head of all the html dokuments it inclueds the meta-tags, 
 * the style sheets, the scripts and the title.
 */
?>
<title>Baka</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="icon" type="image/png" href="img/design/favicon.png" />
<link rel="stylesheet" type="text/css" href="css/styles.css">
<!--<link rel="stylesheet/less" type="text/css" href="css/less/styles.less">-->
<link href='http://fonts.googleapis.com/css?family=Metal+Mania&subset=latin-ext' rel='stylesheet' type='text/css'>
<?php
getDirContent('js/lib');
getDirContent('js');
?>