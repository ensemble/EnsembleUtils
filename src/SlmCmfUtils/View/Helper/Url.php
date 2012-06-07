<?php

/*
 * This is free and unencumbered software released into the public domain.
 * 
 * Anyone is free to copy, modify, publish, use, compile, sell, or
 * distribute this software, either in source code form or as a compiled
 * binary, for any purpose, commercial or non-commercial, and by any
 * means.
 * 
 * In jurisdictions that recognize copyright laws, the author or authors
 * of this software dedicate any and all copyright interest in the
 * software to the public domain. We make this dedication for the benefit
 * of the public at large and to the detriment of our heirs and
 * successors. We intend this dedication to be an overt act of
 * relinquishment in perpetuity of all present and future rights to this
 * software under copyright law.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 * 
 * For more information, please refer to <http://unlicense.org/>
 * 
 * @package    SlmCmfBase
 * @copyright  Copyright (c) 2009-2012 Soflomo (http://www.soflomo.com)
 * @license    http://unlicense.org Unlicense
 */

namespace SlmCmfUtils\View\Helper;

use Zend\View\Helper\Url as BaseUrl;
use Zend\View\Exception;

/**
 * Extend url view helper to provide easy acces to subroutes of pages
 * 
 * The name of all page routes start with the id of the page. As this is an 
 * unknown parameter for the view, this view helper prepends the root part
 * of the route. This happpens when the route name starts with a /.
 * 
 * Therefore common route names like "user" and "admin" will work, but for a 
 * special module Foo with a subroute "view-article", the route name /view-article
 * will be transformed to $id/view-article to match the appropriate page.
 *
 * @package    SlmCmfUtils
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
    public function __invoke($name = null, array $params = array(), array $options = array(), $reuseMatchedParams = false)
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
                $name = substr($routeName, 0, $pos) . $name;
            } else {
                $name = $routeName . $name;
            }
        }
        
        return parent::__invoke($name, $params, $options, $reuseMatchedParams);
    }
}
