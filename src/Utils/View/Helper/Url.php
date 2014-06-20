<?php
/**
 * Copyright (c) 2012 Soflomo http://soflomo.com.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package     Ensemble\Utils
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2012 Soflomo http://soflomo.com.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://ensemble.github.com
 */

namespace Ensemble\Utils\View\Helper;

use Zend\View\Helper\Url as BaseUrl;
use Zend\View\Exception;

/**
 * Extend url view helper to provide easy acces to subroutes of pages
 *
 * The name of all page routes start with the id of the page. As this is an
 * unknown parameter for the view, this view helper prepends the root part
 * of the route. This happpens when the route name argument starts with a /.
 *
 * Therefore common route names like "user" and "admin" will work, but for a
 * special module Foo with a subroute "view-article", the route name /view-article
 * will be transformed to $id/view-article to match the appropriate page.
 *
 * @package    Ensemble\Utils
 * @subpackage View
 * @author     Jurian Sluiman <jurian@soflomo.com>
 */
class Url extends BaseUrl
{
    /**
     * Generates an url given the name of a route.
     *
     * @see    Zend\Mvc\Router\RouteInterface::assemble()
     * @param  string  $name               Name of the route
     * @param  array   $params             Parameters for the link
     * @param  array   $options            Options for the route
     * @param  boolean $reuseMatchedParams Whether to reuse matched parameters
     * @return string Url                  For the link href attribute
     * @throws Exception\RuntimeException  If no RouteStackInterface was provided
     * @throws Exception\RuntimeException  If no RouteMatch was provided
     * @throws Exception\RuntimeException  If RouteMatch didn't contain a matched route name
     */
    public function __invoke($name = null, $params = array(), $options = array(), $reuseMatchedParams = false)
    {
        if ($name !== null && 0 === strpos($name, '/')) {
            if ($this->routeMatch === null) {
                throw new Exception\RuntimeException('No RouteMatch instance provided');
            }

            $routeName = $this->routeMatch->getMatchedRouteName();

            if ($routeName === null) {
                throw new Exception\RuntimeException('RouteMatch does not contain a matched route name');
            }

            if (false !== ($pos = strpos($routeName, '/'))) {
                /**
                 * If we request the name /, it will mean that we need to only get
                 * the root part of the route without any other module route params
                 */
                if ('/' === $name) {
                    $name = substr($routeName, 0, $pos);
                } else {
                    $name = substr($routeName, 0, $pos) . $name;
                }
            } else {
                if ('/' === $name) {
                    $name = $routeName;
                } else {
                    $name = $routeName . $name;
                }
            }
        }

        return parent::__invoke($name, $params, $options, $reuseMatchedParams);
    }
}
