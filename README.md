# gocrypto-php-sdk

> GoCrypto PHP SDK for easy integrations into your ecommerce platforms or any other assorted websites

[![MIT License](https://img.shields.io/packagist/l/cocur/slugify.svg)](http://opensource.org/licenses/MIT)

Please check [GoCrypto's official website](https://gocrypto.com/en/) and their [documentation](https://ecommerce.staging.gocrypto.com/docs).

## Features

- Handles authentication with the GoCrypto API.
- Submits a payment request to the API.
- Provides logic for payment response / validation.
- Built-in nonce tokens using MySQL storage.
- PHP 7.0 or higher.

## Installation

GoCrypto PHP SDK requires CURL and JSON extensions to be present on the system. Download the files and require GoCrypto.class.php.

## Usage

Staging example:

```php
use GoCrypto\SDK\Db;
use GoCrypto\SDK\GoCryptoStaging;

$gc = new GoCryptoStaging(
	new Db('HOST', 'DBNAME', 'USER', 'PASSWORD'),
	'GoCrypto SDK staging test',
	'https://YOUR_SERVER_NAME.com/example_return.php'
);
$gc->addItem('Test product name 1', 'Test product description 1', 1, 10.99);

echo 'Redirect client to: ' . $gc->requestPayment();
```

Production example:

```php
use GoCrypto\SDK\Db;
use GoCrypto\SDK\GoCrypto;

//Prepare instance
$gc = new GoCrypto(
    new Db('HOST', 'DBNAME', 'USER', 'PASSWORD'), 
    'YOUR CLIENT ID', 
    'YOUR CLIENT SECRET', 
    false, 
    'GoCrypto test shop', 
    'https://YOUR_SERVER_NAME.com/example_return.php'
);

//Add items to purchase (EAN and feeable part can be provided here as well)
$gc->addItem('Test product name 1', 'Test product description 1', 1, 10.99);
$gc->addItem('Test product name 2', 'Test product description 2', 1, 5.99);

//Optionally, set your currency
$gc->setCurrency('EUR');

//Optionally, set your locale (Supported: { 'en', 'sl', 'hr', 'tr', 'es', 'ja', 'ru', 'hu', 'sk', 'pt', 'it' })
$gc->setLocale('sl');

echo 'Redirect client to: ' . $gc->requestPayment();
```

You must configure your MySQL database accordingly:
```sql
CREATE TABLE `payment_nonce` (
  `id` bigint UNSIGNED NOT NULL,
  `token` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `expires` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `payment_nonce` ADD PRIMARY KEY (`id`);--
ALTER TABLE `payment_nonce` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
```

If you want to modify table name to anything else, you can do it as constructed below:
```php
new Db('HOST', 'DBNAME', 'USER', 'PASSWORD', 'PAYMENT_NONCE_STORE_TABLE_NAME');
```
(obviously don't forget to change the table name in database)

## License

The MIT License (MIT)

Copyright (c) 2020 THK

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
