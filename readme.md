# Commission Calculator

## Stage:
Refactoring

## Used Technologies:
- WSL/Ubuntu
- VSCode
- PHP 7.4
- Composer
- PSR-4 Composer Autoload
- PHPUnit 9.5

## API requests for:
- bin search: https://lookup.binlist.net/
- exchange rate: https://api.exchangeratesapi.io/latest

## Execute commands to proceed with:
1. `composer install` - generate autoloader and install dependancies
2. `php app.php input.txt` - run comission converter
3. `vendor/bin/phpunit tests/Unit` - run unit tests
4. `vendor/bin/phpunit tests/Functional` - run functional tests