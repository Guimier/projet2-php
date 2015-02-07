<!DOCTYPE html>
<html>
	<head>
		<title><?php echo htmlspecialchars( $this->getTitle() ); ?></title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
<?php if ( get_called_class() !== 'IndexPage' ) { ?>
		<nav><a href=".">Revenir à l’index</a></nav>
<?php } ?>
		<?php echo $this->getContent(); ?>
	</body>
</html>
