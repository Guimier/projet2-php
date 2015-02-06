<!DOCTYPE html>
<html>
	<head>
		<title><?php echo htmlspecialchars( $this->getTitle() ); ?></title>
		<link rel="stylesheet" href="styles.css" />
	</head>
	<body>
		<?php echo $this->getContent(); ?>
	</body>
</html>
