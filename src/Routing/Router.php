<?php

namespace whereof\hprose\Routing;

use whereof\hprose\Support\MemoryStorage;
use whereof\hprose\Support\RefHelper;

/**
 * Class Router
 * @package whereof\hprose\Routing
 */
class Router
{

    /**
     * @param $path
     * @return Router
     */
    public function addPath($path)
    {
        $in_class = RefHelper::getInstance()->getNameSpaceClass($path);
        if (!empty($in_class)) {
            foreach ($in_class as $class) {
                $this->add($class);
            }
        }
        return $this;
    }

    /**
     * @param $action
     * @param string $alias
     * @param array $options
     * @return $this
     */
    public function add($action, string $alias = '', array $options = [])
    {
        $actionAliasArgs = RefHelper::getInstance()->actionAliasArgs($action, $alias);
        if (!empty($actionAliasArgs['sign']) && $actionAliasArgs['sign'] === 'callable') {
            unset($actionAliasArgs['sign']);
            MemoryStorage::getInstance()->push($actionAliasArgs);
            $this->addFunction($action, $actionAliasArgs['alias'], $options);
        }
        if (!empty($actionAliasArgs['sign']) && $actionAliasArgs['sign'] === 'class') {
            unset($actionAliasArgs['sign']);
            foreach ($actionAliasArgs as $arg) {
                MemoryStorage::getInstance()->push($arg);
                $this->addInstanceMethods(new $arg['class'](), $arg['alias'], $options);
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getClassMethodArgs()
    {
        return MemoryStorage::getInstance()->get();
    }

    /**
     * https://github.com/hprose/hprose-php/wiki/06-Hprose-%E6%9C%8D%E5%8A%A1%E5%99%A8
     * @param $object
     * @param $alias
     * @param $options
     * @return Router
     */
    protected function addInstanceMethods($object, $alias, $options)
    {
        app('hprose.socket.server')->addInstanceMethods($object, '', $alias, $options);
        return $this;
    }

    /**
     * https://github.com/hprose/hprose-php/wiki/06-Hprose-%E6%9C%8D%E5%8A%A1%E5%99%A8
     * @param callable $action
     * @param string $alias
     * @param array $options
     * @return Router
     */
    protected function addFunction(callable $action, string $alias, array $options)
    {
        app('hprose.socket.server')->addFunction($action, $alias, $options);
        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return Router
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        app('hprose.socket.server')->$name(...$arguments);
        return $this;
    }
}
