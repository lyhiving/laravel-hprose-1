<?php


namespace whereof\laravel\hprose\Support;


use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionMethod;

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
        $phpfiles       = $this->filterExtPath($path, ['php']);
        $namespaceClass = [];
        foreach ($phpfiles as $file) {
            $namespaceClass[] = $this->phpFileNameSpaceClass($file['pathname']);
        }
        return $namespaceClass;
    }

    /**
     * 获取php文件的命名空间+类名
     * @param $file
     * @return bool|mixed|string
     */
    public function phpFileNameSpaceClass($file)
    {
        if (!is_file($file)) {
            throw new InvalidArgumentException("The file argument must be a file: $file");
        }
        $namespace         = $class = "";
        $getting_namespace = $getting_class = false;
        foreach (token_get_all(file_get_contents($file)) as $token) {
            if (is_array($token) && $token[0] == T_NAMESPACE) {
                $getting_namespace = true;
            }
            if (is_array($token) && $token[0] == T_CLASS) {
                $getting_class = true;
            }
            if ($getting_namespace === true) {
                if (is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {
                    $namespace .= $token[1];
                } else if ($token === ';') {
                    $getting_namespace = false;

                }
            }
            if ($getting_class === true) {
                if (is_array($token) && $token[0] == T_STRING) {
                    $class = $token[1];
                    break;
                }
            }
        }
        return $namespace ? $namespace . '\\' . $class : $class;
    }

    /**
     *
     * FileHelper::filterExtPath("path",['php'])
     * @param $dir
     * @param array $ext
     * @return array
     */
    public function filterExtPath($dir, array $ext = [])
    {
        if (!is_dir($dir)) {
            throw new InvalidArgumentException("The dir argument must be a directory: $dir");
        }
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST);
        $files    = [];
        foreach ($iterator as $file) {
            $foo = empty($ext) ? true : in_array($file->getExtension(), $ext);
            if ($file->isFile() && $foo) {
                $tmpFile['pathname'] = $file->getRealPath();
                $files[]             = $tmpFile;
            }
        }
        return $files;
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