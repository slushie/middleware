<?php
/**
 * Author: Josh Leder <slushie@gmail.com>
 * Created: 11/8/13 @ 6:00 PM
 */


namespace Slushie\Middleware;


interface BeforeInterface {
  /**
   * Called before requests are routed.
   *
   * @param $request
   * @return \Symfony\Component\HttpFoundation\Response|null
   */
  public function onBefore($request);
} 