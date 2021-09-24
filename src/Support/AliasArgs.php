<?php


namespace whereof\laravel\hprose\Support;


use ReflectionMethod;
use whereof\Helper\FileHelper;

/**
 * Class Ref
 * @package whereof\laravel\hprose\Support
 */
class AliasArgs
{
    use Singleton;

    /**
     * 允许被反射的方法
     * @var int
     */
    public static $allow_get_methods = ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED | ReflectionMethod::IS_PRIVATE | ReflectionMethod::IS_STATIC;

    /**
     * @param $action
     * @param string $alias
     * @return array
     */
    public function actionAliasArgs($action, $alias = '')
    {
        try {
            $getType = gettype($action);
            if ($getType === 'object' && is_callable($action)) {
                $ref            = $this->reflectionCallable($action);
                $result         = [
                    'class'  => '',
                    'alias'  => $alias,
                    'method' => '',
                    'args'   => $this->getRefParameterArr($ref->getParameters()),
                ];
                $result['sign'] = 'callable';
                return $result;

            } elseif ($getType === 'string' && class_exists($action)) {
                $ref    = $this->reflectionClass($action);
                $result = [];
                foreach ($ref->getMethods(self::$allow_get_methods) as $method) {
                    $result[] = [
                        'class'  => $action,
                        'method' => $method->getName(),
                        'alias'  => strtolower($this->namespaceSlash2nderscore($action)),
                        'args'   => $this->getRefParameterArr($ref->getMethod($method->getName())->getParameters())
                    ];
                }
                $result['sign'] = 'class';
                return $result;
            }
        } catch (\ReflectionException $e) {

        } catch (\ArgumentCountError $e) {

        } catch (\Exception $e) {

        }
        return ['sign' => 'undefined'];
    }


    /**
     * 获取目录下所有的php文件并返回命名空间
     * @param $path
     * @return array
     */
    public function getNameSpaceClass($path)
    {
        $phpfiles       = FileHelper::filterExtPath($path, ['php']);
        $namespaceClass = [];
        foreach ($phpfiles as $file) {
            $namespaceClass[] = FileHelper::phpFileNameSpaceClass($file['pathname']);
        }
        return $namespaceClass;
    }


    /**
     * @param callable $name
     * @return \ReflectionFunction
     * @throws \ReflectionException
     */
    public function reflectionCallable(callable $name)
    {
        return new \ReflectionFunction($name);
    }

    /**
     * @param $argument
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    public function reflectionClass($argument)
    {
        return new \ReflectionClass($argument);
    }

    /**
     * @param $namespace
     * @return mixed
     */
    protected function namespaceSlash2nderscore($namespace)
    {
        return str_replace("\\", "_", $namespace);
    }

    /**
     * @param $object
     * @param string $column
     * @return array
     */
    protected function getRefParameterArr($object, $column = 'name')
    {
        return array_column(json_decode(json_encode($object), true), $column);
    }
}