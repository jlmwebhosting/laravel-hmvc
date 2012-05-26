<?php

Autoloader::map(array(
	'HMVC\\HMVC' => __DIR__.DS.'hmvc.php',
));

Autoloader::alias('HMVC\\HMVC', 'HMVC');
