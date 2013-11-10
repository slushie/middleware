<?php
/**
 * Author: Josh Leder <slushie@gmail.com>
 * Created: 11/8/13 @ 6:06 PM
 */


namespace Slushie\Middleware;



interface AfterInterface {
  /**
   * Called after requests are processed.
   *
   * @param $request
   * @param $response
   * @return void
   */
  public function onAfter($request, $response);
} 