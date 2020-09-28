<?php

	use GoCrypto\SDK\Db;
	use GoCrypto\SDK\GoCryptoStaging;

	require_once __DIR__ . '/src/GoCrypto.class.php';

	$gc = new GoCryptoStaging(
		new Db('HOST', 'DBNAME', 'USER', 'PASSWORD'),
		'GoCrypto SDK staging test',
		'https://YOUR_SERVER_NAME.com/example_return.php'
	);

	//OR, for production purposes:
	#$gc = new GoCrypto(new Db('HOST', 'DBNAME', 'USER', 'PASSWORD'), 'c83aa8ac-b29a-41a6-b039-3ed5ac69acff', 'xzOOGVskFG2IeV2W3SFFFbEPC8vMDflz2k6LjrNXDCM4BFvH', false, 'GoCrypto test shop', 'https://yourshop.com/success', 'https://yourshop.com/cancel');

	//Adding items
	$gc->addItem('Test product name 1', 'Test product description 1', 1, 10.99);
	$gc->addItem('Test product name 2', 'Test product description 2', 1, 6.99);

	//Set properties (optional)
	$gc->setCurrency('EUR');
	$gc->setLocale('sl');


	echo 'Redirect client to: ' . $gc->requestPayment();