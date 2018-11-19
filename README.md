# MiddlewareBundle

[![license](https://img.shields.io/github/license/IndraGunawan/middleware-bundle.svg?style=flat-square)](https://github.com/IndraGunawan/middleware-bundle/blob/master/LICENSE.md)
[![Travis](https://img.shields.io/travis/IndraGunawan/middleware-bundle.svg?style=flat-square)](https://travis-ci.org/IndraGunawan/middleware-bundle)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/IndraGunawan/middleware-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/IndraGunawan/middleware-bundle/?branch=master)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/IndraGunawan/middleware-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/IndraGunawan/middleware-bundle/?branch=master)
[![Source](https://img.shields.io/badge/source-IndraGunawan%2Fmiddleware--bundle-blue.svg)](https://github.com/IndraGunawan/middleware-bundle)
[![Packagist](https://img.shields.io/badge/packagist-indragunawan%2Fmiddleware--bundle-blue.svg)](https://packagist.org/packages/indragunawan/middleware-bundle)

Middleware bundle provide simple implementation of [symfony before and after filter](https://symfony.com/doc/current/event_dispatcher/before_after_filters.html) by using annotation. this implementation is inspired by Laravel Middleware.

## Installation

If your project already uses Symfony Flex, execute this command to
download, register and configure the bundle automatically:

```bash
composer require indragunawan/middleware-bundle
```

If you install without using Symfony Flex, first add the bundle by using composer then enable the bundle by adding `new Indragunawan\MiddlewareBundle\IndragunawanMiddlewareBundle()` to the list of registered bundles in the app/AppKernel.php file of your project.

## Create middleware service

```php
<?php
// src/Middleware/Subscribed.php
use Indragunawan\MiddlewareBundle\Middleware\BeforeFilterInterface;
use Indragunawan\MiddlewareBundle\Middleware\AfterFilterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Subscribed implements BeforeFilterInterface //, AfterFilterInterface // you can implement multi filter at once
{
    public static function supports()
    {
        return 'subscribed'; // return the filter name
        // return ['subscribed', 10] // return [filter_name, priority] same like usual Symfony event
    }

    // implement this method for BeforeFilter
    public function onBeforeFilter(Request $request, array $controller, ?int $requestType)
    {
        // your logic
    }

    // implement this method for AfterFilter
    public function onAfterFilter(Request $request, Response $response, array $controller, ?int $requestType)
    {
        // your logic
    }
}
```

## Usage

You only need to create the annotation on class level.

```php
<?php
// app/Controller/HomepageController.php
use Indragunawan\MiddlewareBundle\Annotation\BeforeFilter;
use Indragunawan\MiddlewareBundle\Annotation\AfterFilter;

/**
 * @BeforeFilter("subscribed") // the 'subscribed' middleware will execute before every actions at this controller.
 * @BeforeFilter("subscribed", only="index") // the 'subscribed' middleware will execute ONLY before 'index' action at this controller.
 * @BeforeFIlter("subscribed", except="create") // the 'subscribed' middleware will execute before every actions at this controller EXCEPT 'create' action.
 *
 * @AfterFilter({"subscribed", "other"}) // the 'subscribed' and 'other' middleware will execute after every actions at this controller.
 * @AfterFilter({"subscribed", "other"}, only={"index","delete"}) // the 'subscribed' and 'other' middleware will execute ONLY after 'index' and 'delete' action at this controller.
 * @AfterFilter({"subscribed", "other"}, except={"create","update"}) // the 'subscribed' and 'other' middleware will execute after every actions at this controller EXCEPT 'create' and 'update' action.
 *
 * BeforeFilter and AfterFilter receive same arguments format.
 */
class HomepageController extends AbstractController
{
    // your action
}
```

License
-------

This bundle is under the MIT license. See the complete [license](LICENSE)
