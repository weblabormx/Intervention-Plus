# Intervention Plus

Package that extends `intervention/image` and adds new functions.

This package requires Intervention, so intervention will be always updated on this package and are completely separed packages.

## Installation

You can install the package via composer:

```bash
composer require weblabormx/intervention-plus
```

## Usage

Use it exactly the same that intervention static

```php
use WeblaborMX\InterventionPlus\Image;

$image = Image::make('tests/picture.jpg')->resize(300, 200);
```

## New functions

- **copy()**: To clone the object easily
- **resizeWithRatio($width, $height)**: Resize keeping the ratio of the image

### Testing

``` bash
phpunit test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email carlosescobar@weblabor.mx instead of using the issue tracker.

## Emailware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending an email with the url of the website in production to add it to our website

Our email address is: carlosescobar@weblabor.mx

We publish all received emails [on our company website](http://weblabor.mx).

## Credits

- [Carlos Escobar](https://github.com/skalero01)
- [All Contributors](../../contributors)

## Support us

Weblabor is a webdesign agency based in México. You'll find an overview of all our open source projects [on our website](http://weblabor.mx).

Does your business depend on our contributions? Reach out and support us on [Paypal](http://paypal.me/weblabormx). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
