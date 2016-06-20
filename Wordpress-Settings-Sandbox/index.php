<!DOCTYPE html>
<html>
<head>
	<title>The Complete Guide To The Settings API | Sandbox Theme</title>
</head>
<body>


<?php if(!get_option('show_header')) { /*
 If the conditional evaluates to true (that is, the option has been checked on the General Settings page),
 then the element will not be displayed; otherwise, the element will be displayed. */ ?>
	<div id = "header" >
		<h1 > Sandbox Header </h1 >
	</div ><! -- /#header -->
<?php } ?>

<?php if(!get_option('show_content')) { ?>
	<div id="content">
		<p>This is theme content.</p>
	</div><!-- /#content -->
<?php } ?>

<?php if(!get_option('show_footer')) { ?>
	<div id="footer">
		<p>&copy; <?php echo date('Y'); ?> All Rights Reserved.</p>
	</div><!-- /#footer -->
<?php } ?>


</body>
</html>