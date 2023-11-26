# Etsy Open API SDK

This is a simple SDK for [Etsy's Open API](https://developers.etsy.com/) in PHP.



## Prerequisites

You will need:

* An Etsy account, be approved to use Etsy Open API
* An applicationn being written for [PHP](https://www.php.net/) 8.2 (or higher up to 9.0)
* [Composer](https://getcomposer.org/)



## Installation

Using composer, run:

```bash
composer require christianjbrown/etsy-open-api-sdk-php
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
$refreshTokenStore = new DatabaseKeyValueStore($entityManager, RefreshTokens::class, 'etsy-refresh-token');

$api = new Api($shopId, $keyString, $refreshTokenStore);
$receiptsApi = $api->getReceiptsApi();

$resultSet = $receiptsApi->getResultSet();
$receipts = $resultSet->getResults();
```



## Dependencies

This library uses:
* [christianjbrown/json-api-client](https://github.com/christianjbrown/json-api-client) for making HTTP requests
* [christianjbrown/key-value-store](https://github.com/christianjbrown/key-value-store) for storing refresh tokens
* [christianjbrown/oauth2-client](https://github.com/christianjbrown/oauth2-client) for getting access tokens
* [christianjbrown/user-friendly-exception](https://github.com/christianjbrown/user-friendly-exception) for raising user-friendly exceptions
* [psr/http-client](https://github.com/php-fig/http-client) for HTTP client interfaces
* [symfony/dependency-injection](https://github.com/symfony/dependency-injection) for dependency injection


During development, it also uses:

* [christianjbrown/phpcs-wrapper](https://github.com/christianjbrown/phpcs-wrapper) for checking code style via `composer check-style`
* [christianjbrown/php-cs-fixer-wrapper](https://github.com/christianjbrown/php-cs-fixer-wrapper) for code style cleanup via `composer fix-style`
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