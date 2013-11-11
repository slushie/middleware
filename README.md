middleware
==========

Laravel 4 Middleware Configuration Loader

This package provides a simple configuration point where you can
add middleware to the Laravel 4 request processing pipeline.

It works by adding callbacks to the `App::before()` and `App::after()`
application-level events. Classes are registered in the `app.middleware`
configuration key array (that is, `middleware` is an array defined in
the `app/config/app.php` file).

How To Use
----------

First, create a class that implements at least one of
`Slushie\Middleware\BeforeInterface` or `Slushie\Middleware\AfterInterface`.
Then implement the `onBefore($request)` and `onAfter($request, $response)`
methods. You can return a non-`null` value from an `onBefore` handler to
halt request processing, just like with Laravel's `App::before()`. Return
values for `onAfter()` are ignored.

    class RedirectBeforeMiddleware implements BeforeInterface {
      public function onBefore($request) {
        if ($request->query('mobile') == 'redirect') {
          return Redirect::to('/mobile');
        }
      }
    }

    class TimingMiddleware implements BeforeInterface, AfterInterface {
      public function onBefore($request) {
        $this->start_time = time();
      }

      public function onAfter($request, $response) {
        $duration = time() - $this->start_time;
        Log::info("Processing {$request->url()} took {$duration} sec");
      }
    }

Next, configure your application to load the `MiddlewareServiceProvider`
as well as adding your own middleware classes to the `middleware` configuration
key.

For example, your `app/config/app.php` file might include:

    'providers' => array(
      // .. snip ..

      'Slushie\Middleware\MiddlewareServiceProvider'
    ),

    'middleware' => array(
      'RedirectBeforeMiddleware',
      'TimingMiddleware'
    )

How It Works
------------

The `MiddlewareServiceProvider` creates objects from all classes
in the `app.middleware` array using the IoC container. Any class
that implements `BeforeInterface` is used as `before` middleware,
and any class that implements `AfterInterface` is used as an
`after` middleware. Of course, a class can implement both interfaces
to be used at both control points.

Rationale
---------

Although Laravel provides a simple method to apply middleware in
each application via the `App::before()` and `App::after()` methods,
this approach does not lend itself to simple configuration.

This means that packages that need to apply middleware to the requests
must do so in their service provider instances, which enforces this
behaviour in user applications.

This package provides a means for application developers and administrators
to selectively enable and disable middleware without resorting to editing
application code (such as `app/filters.php`).