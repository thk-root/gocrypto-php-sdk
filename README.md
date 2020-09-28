# gocrypto-php-sdk

> GoCrypto PHP SDK for easy integrations into your ecommerce platforms or any other assorted websites

[![MIT License](https://img.shields.io/packagist/l/cocur/slugify.svg)](http://opensource.org/licenses/MIT)

Please check [GoCrypto's official website](https://gocrypto.com/en/) and their [documentation](https://ecommerce.staging.gocrypto.com/docs).

## Features

- Handles authentication with the GoCrypto API.
- Submits a payment request to the API.
- PHP 7.0 or higher.

## Installation

GoCrypto PHP SDK requires CURL and JSON extensions to be present on the system. Download the files and require GoCrypto.class.php.

## Usage

Staging example:

```php
use GoCrypto\SDK\GoCryptoStaging;

$gc = new GoCryptoStaging('GoCrypto SDK staging test', 'https://yourshop.com/success');
$gc->addItem('Test product name 1', 'Test product description 1', 1, 10.99);

echo 'Redirect client to: ' . $gc->requestPayment();
```

Production example:

```php
use GoCrypto\SDK\GoCrypto;

//Prepare instance
$gc = new GoCrypto('CLIENT ID', 'CLIENT SECRET', false, 'MY SHOP NAME', 'RETURN URL', 'CANCEL URL');

//Add items to purchase (EAN and feeable part can be provided here as well)
$gc->addItem('Test product name 1', 'Test product description 1', 1, 10.99);
$gc->addItem('Test product name 2', 'Test product description 2', 1, 5.99);

//Optionally, set your currency
$gc->setCurrency('EUR');

//Optionally, set your locale (Supported: { 'en', 'sl', 'hr', 'tr', 'es', 'ja', 'ru', 'hu', 'sk', 'pt', 'it' })
$gc->setLocale('sl');

echo 'Redirect client to: ' . $gc->requestPayment();
```

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
