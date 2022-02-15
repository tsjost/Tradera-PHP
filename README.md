[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)

# Setup
```bash
$ composer require tsjost/Tradera
```

# Usage
```php
<?php
$C = new tsjost\Tradera\Category(20);
foreach ($C->get_items() as $item) {
	echo "\"{$item->shortDescription}\" available for {$item->price} SEK until {$item->endDate}\n";
}
```

# Development
## Automated Testing
Clone the repo and:
```bash
$ composer install
$ composer test
```
