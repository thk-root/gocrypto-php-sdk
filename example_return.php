<?php

	use GoCrypto\SDK\Db;
	use GoCrypto\SDK\GoCryptoReturn;

	require_once __DIR__ . '/src/GoCrypto.class.php';

	$r = new GoCryptoReturn(new Db('HOST', 'DBNAME', 'USER', 'PASSWORD'));
	echo $r->isValid() ? 'Payment was successful' : 'Payment was unsuccessful';