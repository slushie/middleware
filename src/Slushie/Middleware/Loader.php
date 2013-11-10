<?php
/**
 * Author: Josh Leder <slushie@gmail.com>
 * Created: 11/9/13 @ 10:45 PM
 */


namespace Slushie\Middleware;


use Illuminate\Foundation\Application;

class Loader {
  /** @var AfterInterface[] */
  protected $after = array();

  /** @var BeforeInterface[] */
  protected $before = array();

  /**
   * Create the middleware loader.
   *
   * @param Application $app
   */
  function __construct(Application $app) {
    $this->app = $app;
  }

  /**
   * Register all configured middleware classes with the
   * appropriate App-level events.
   */
  public function register() {
    $this->registerMiddleware();

    $this->app->before($this->createBeforeFilter());
    $this->app->after($this->createAfterFilter());
  }

  /**
   * Create middleware instances from the array of
   * class names in the "app.middleware" config key.
   */
  protected function registerMiddleware() {
    $middleware = $this->app['config']['app.middleware'];

    foreach ($middleware as $class_name) {
      $filter = app($class_name);
      if ($filter instanceof BeforeInterface) {
        array_push($this->before, $filter);
      }
      if ($filter instanceof AfterInterface) {
        array_push($this->after, $filter);
      }
    }
  }

  /**
   * Generate a callback function that iterates over
   * all BeforeInterface middleware.
   *
   * @return callable
   */
  protected function createBeforeFilter() {
    $before = $this->before;

    // closure that iterates over all before filters
    return function ($request) use ($before) {
      foreach ($before as $filter) {
        $response = $filter->onBefore($request);
        if (!is_null($response)) {
          return $response;
        }
      }

      return null;
    };
  }

  /**
   * Generate a callback function that iterates over
   * all AfterInterface middleware.
   *
   * @return callable
   */
  protected function createAfterFilter() {
    $after = $this->after;

    return function ($request, $response) use ($after) {
      foreach ($after as $filter) {
        $filter->onAfter($request, $response);
      }
    };
  }

}