<?php
class Container
{
    protected static $_singleton = [];
    public static function getInstance($class_name, $params = [])
    {
        // 获取反射实例
        $reflector = new ReflectionClass($class_name);
        // 获取反射实例的构造方法
        $constructor = $reflector->getConstructor();
        // print_r( $constructor);exit;
        // 获取反射实例构造方法的形参
        $di_params = $constructor ? self::_getDiParams($constructor->getParameters()) : [];
        
        $di_params = array_merge($di_params, $params);
        // 创建实例
        return $reflector->newInstanceArgs($di_params);
    }
    /**
     * 获取实例
     */
    public static function getSingleton($class_name){
        return array_key_exists($class_name,self::$_singleton) ? self::$_singleton[$class_namme] : null;
    }
    /**
     * 设置一个新的实例
     */
    public static function singleton($instance){
      if (!is_object($instance)) {
        throw new InvalidArgumentException("Object need!");
      }
      $class_name = get_class($instance);
      // singleton not exist, create
      if ( ! array_key_exists($class_name, self::$_singleton)) {
          self::$_singleton[$class_name] = $instance;
      }
    }
    /**
     * 销毁一个实例
     */
    public static function delSingleton($class_name){
      self::$_singleton[$class_namme] = null;
    }

      /**
       * 组装成函数
       * */
    protected static function _getDiParams(array $params)
    {
        $di_params = [];
        foreach ($params as $param) {
            $class = $param->getClass();
            if ($class) {
                // check dependency is a singleton instance or not
                $singleton = self::getSingleton($class->name);
                $di_params[] = $singleton ? $singleton : self::getInstance($class->name);
            }
        }
        return $di_params;
    }

    public static function run($class_name,$method_name,$params = [],$construct_params = []){
      if(!class_exists($class_name)){
        throw new \Exception("Class $class_name is not found!");
      }
      if(!method_exists($class_name,$method_name)){
        throw new \Exception("method $method_name is not found in $class_name");
      }
      //获取实例
      $instacne = self::getInstance($class_name,$construct_params);
      // 获取反射实例
      $reflector = new ReflectionClass($class_name);
      // 获取方法
      $reflectorMethod = $reflector->getMethod($method_name);
      // 查找方法的参数
      $di_params = self::_getDiParams($reflectorMethod->getParameters());

      return call_user_func_array([$instacne,$method_name],array_merge($di_params, $params));
    }
}