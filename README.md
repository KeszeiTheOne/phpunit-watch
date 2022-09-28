# phpunit-watch

This library provide a function for automatically run phpunit test while file changes.

## Getting Started

You need to install before the [phpunit](https://phpunit.de/) to your project.
I tested with phpunit >=7 version, but maybe it works with older ones too. 
The script look for in your project the phpunit and use too.

## Requirements
* PHP >= 7.3
* inotify php extension
* Linux like system where inotify extension is available
* phpunit/phpunit composer package

### Prerequisites

You need to install phpunit library.

```bash
composer require phpunit/phpunit --dev 
```

### Installing

Install phpunit-watcher library with composer.

```bash
composer require keszei/phpunit-watcher --dev
```

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/KeszeiTheOne/phpunit-watch/tags).

## Authors

* **Balázs Keszei** - *Initial work* - [KeszeiTheOne](https://github.com/KeszeiTheOne)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
