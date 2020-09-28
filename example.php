<?php

	use GoCrypto\SDK\GoCryptoStaging;

	require_once __DIR__ . '/src/GoCrypto.class.php';
	require_once __DIR__ . '/src/GoCryptoStaging.class.php';

	$gc = new GoCryptoStaging('GoCrypto SDK staging test', 'https://yourshop.com/success');

	//OR, for production purposes:
	//$gc = new GoCrypto('GoCrypto SDK staging test', 'c83aa8ac-b29a-41a6-b039-3ed5ac69acff', 'xzOOGVskFG2IeV2W3SFFFbEPC8vMDflz2k6LjrNXDCM4BFvH', false, 'GoCrypto test shop', 'https://yourshop.com/success', 'https://yourshop.com/cancel');

	//Adding items
	$gc->addItem('Test product name 1', 'Test product description 1', 1, 10.99);
	$gc->addItem('Test product name 2', 'Test product description 2', 1, 6.99);

	//Set properties (optional)
	$gc->setCurrency('EUR');
	$gc->setLocale('sl');


	echo 'Redirect client to: ' . $gc->requestPayment();