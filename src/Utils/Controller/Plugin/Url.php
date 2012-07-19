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

namespace Ensemble\Utils\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\Url as BaseUrl;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\Mvc\Exception;
use Zend\Mvc\Router\RouteMatch;

/**
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Controller
 */
class Url extends BaseUrl
{
    protected $routeMatch;

    /**
     * {@inheritdoc}
     */
    public function fromRoute($route, array $params = array(), array $options = array())
    {
        if ($route !== null && 0 === strpos($route, '/')) {
            if ($this->getRouteMatch() === null) {
                throw new Exception\RuntimeException('No RouteMatch instance provided');
            }

            $routeName = $this->getRouteMatch()->getMatchedRouteName();

            if ($routeName === null) {
                throw new Exception\RuntimeException('RouteMatch does not contain a matched route name');
            }

            if (false !== ($pos = strpos($routeName, '/'))) {
                /**
                 * If we request the name /, it will mean that we need to only get
                 * the root part of the route without any other module route params
                 */
                if ('/' === $route) {
                    $route = substr($routeName, 0, $pos);
                } else {
                    $route = substr($routeName, 0, $pos) . $route;
                }
            } else {
                $route = $routeName . $route;
            }
        }

        return parent::fromRoute($route, $params, $options);
    }

    public function getRouteMatch()
    {
        if ($this->routeMatch) {
            return $this->routeMatch;
        }

        $controller = $this->getController();
        if (!$controller instanceof InjectApplicationEventInterface) {
            throw new Exception\DomainException('Url plugin requires a controller that implements InjectApplicationEventInterface');
        }

        $event     = $controller->getEvent();
        $routMatch = $event->getRouteMatch();
        if (!$routMatch instanceof RouteMatch) {
            throw new Exception\DomainException('Redirect plugin requires event compose a rout match');
        }
        $this->routMatch = $routMatch;
        return $this->routMatch;
    }
}
