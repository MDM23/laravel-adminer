# Adminer for Laravel

[![Build Status](https://travis-ci.org/MDM23/laravel-adminer.svg?branch=master)](https://travis-ci.org/MDM23/laravel-adminer)
[![Maintainability](https://api.codeclimate.com/v1/badges/a5d76d5b32f6b211ec5e/maintainability)](https://codeclimate.com/github/MDM23/laravel-adminer/maintainability)

Adminer for Laravel development. Latest versions, automatically released, fully
tested with PHP 7.1+.

## Motivation

I am a passionated user of Arch Linux and I am using Laravel frequently in my
day job. Arch is awesome because it always provides you the latest and greatest
software. During development on Laravel-based projects, I like to use adminer to
access my database easily. There are several great projects available that let
you integrate adminer into Laravel. Unfortunately, there is always a lack of
tests and it often takes some time until they provide the latest release of
adminer.

As PHP 7.3 has been released and I was unable to open adminer again for some
weeks, I decided to take action. My idea was to create a new package that
automatically builds, tests and deploys a new version of itself after a new
release of adminer took place. The result can be found in this repository!

## Installation

The package is not yet released on Packagist. If you want to try it out at this
point, you need to add the following to your `composer.json`:

```json
{
  "repositories": {
    "mdm23/laravel-adminer": {
      "type": "vcs",
      "url": "https://github.com/MDM23/laravel-adminer"
    }
  }
}
```

After doing this, you can install it by running:

```bash
composer require --dev mdm23/laravel-adminer
```

For Laravel 5.3+ you do not need to do anything further. The pacakge should be
automatically discovered and used.

## Roadmap

- [ ] Run a nightly build job to detect new adminer releases
- [ ] Create an automated release process
- [ ] Publish the package to Packagist
- [ ] Configurable auto-login feature
- [ ] Only run in local environments due to security reasons
- [ ] Check if integration tests would be sufficent
- [ ] Extend the tests

## Credits

Thanks to [@lhq0826](https://github.com/lhq0826) for coming up with a regular
expression to refactor function names that would otherwise conflict with
Laravel.

Thank you to the authors of similar projects where I got some inspiration from:

- [adminer-for-laravel](https://github.com/lhq0826/adminer-for-laravel)
  by [@lhq0826](https://github.com/lhq0826)
- [Laravel-Adminer](https://github.com/miroc/Laravel-Adminer)
  by [@miroc](https://github.com/miroc)
- [laravel-adminer](https://github.com/onecentlin/laravel-adminer)
  by [@onecentlin](https://github.com/onecentlin)

## License

The project is open-source software licensed under the
[MIT license](https://opensource.org/licenses/MIT).
