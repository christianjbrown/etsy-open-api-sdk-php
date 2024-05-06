# Etsy Open API SDK

This is a simple SDK for [Etsy's Open API](https://developers.etsy.com/) in PHP.



## Prerequisites

You will need:

* An Etsy account, be approved to use Etsy Open API
* An application being written for [PHP](https://www.php.net/) 8.2 (or higher up to 9.0)
* [Composer](https://getcomposer.org/)



## Installation

Using composer, run:

```bash
composer require christianjbrown/php-etsy-open-api-sdk
```



## Usage

```php
use ChristianBrown\Etsy\Api;
use ChristianBrown\KeyValueStore\DatabaseKeyValueStore;

$shopId = getenv('ETSY_SHOP_ID');
$keyString = getenv('ETSY_KEY_STRING');

// Replace with your own key-value token value store,
// see christianbrown/key-value-store for examples.
// Note: You will need to generate the first refresh token manually,
// and then store it in the key-value store.
$accessTokenStore = new MemoryKeyValueStore();
$refreshTokenStore = new DatabaseKeyValueStore($entityManager, RefreshTokens::class, 'etsy-refresh-token');

$api = new Api($shopId, $keyString, $accessTokenStore, $refreshTokenStore);
$receiptsApi = $api->getReceiptsApi();

$resultSet = $receiptsApi->getResultSet();
$receipts = $resultSet->getResults();
```


## Dependencies

This library uses:
* [christianjbrown/php-json-api-client-lib](https://github.com/christianjbrown/php-json-api-client-lib) for making HTTP requests
* [christianjbrown/php-key-value-store-lib](https://github.com/christianjbrown/php-key-value-store-lib) for storing refresh tokens
* [christianjbrown/php-oauth2-client-lib](https://github.com/christianjbrown/php-oauth2-client-lib) for getting access tokens
* [christianjbrown/php-user-friendly-exception-lib](https://github.com/christianjbrown/php-user-friendly-exception-lib) for raising user-friendly exceptions
* [psr/http-client](https://github.com/php-fig/http-client) for HTTP client interfaces
* [symfony/dependency-injection](https://github.com/symfony/dependency-injection) for dependency injection


During development, it also uses:

* [christianjbrown/christianjbrown/php-code-quality-scripts](https://github.com/christianjbrown/christianjbrown/php-code-quality-scripts) for fixing and checking code style via `composer fix-style` and `composer check-style`
* [phpunit/phpunit](https://github.com/sebastianbergmann/phpunit) for unit testing via `composer test`


## Contributing

Before creating a pull request, ensure that you have

1. Fixed your code style with `composer fix-style`, and checked style with `composer check-style`.
2. Checked test coverage with `composer test`. 100% line, branch, function and method coverage is required. 100% path coverage is encouraged.


## License

Copyright © 2023 Christian Brown

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.