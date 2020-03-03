# IocContainer

## 介绍

一个简单好用的Container类，对于学习理解依赖注入有个更好的帮助。

Ioc容器提供了单例操作、获取实例、执行实例方法(依赖注入)等功能，可以使用“Ioc容器”解决多重依赖问题。

## 获取实例

Container类提供了getInstance方法操作返回实例，由 getInstance 方法获取的实例会自动进行依赖注入。
如果注入的实例中也有依赖。会通过递归返回所依赖的依赖注入，直至所有的依赖注入完成。
````
class C{
    public $num = 1;
}
class A{
    protected $num = 10;
    public function __construct(C $c){
        $this->num = $this->num + $c->num;
    }
    public function getNum(){
        return $this->num;
    }
}
class B{
    protected $total;
    public function __construct(A $a,$num){
        $this->total = $a->getNum() + $num;
    }
    public function getTotal(){
        return $this->total;
    }
}
$test = Container::getInstance(B::class);
print_r($test);
````
如果要获取实例的构造方法有其他参数，可以通过 getInstance 方法的第二个参数传入
````
echo "<br/>";
$b = Container::getInstance(B::class, [10]);
print_r($b->getTotal()); // result is 20
````
## 执行实例方法
在run方法里，通过回调函数运行实例的方法。如果该方法中有依赖在，则注入依赖。
````
class C{
    public $num = 1;
}
class A{
    protected $num = 10;
    public function __construct(C $c){
        $this->num = $this->num + $c->num;
    }
    public function getNum(){
        return $this->num;
    }
}
class B{
    protected $total;
    public function __construct(A $a,$num){
        $this->total = $a->getNum() + $num;
    }
    public function getTotal(){
        return $this->total;
    }
}
echo Container::run(B::class, 'getTotal' ,[],[10]);
````
通过run方法的第三个参数，给运行的方法传入额外参数；
通过第四个参数，给运行实例中的构造方法进行参数传递。

## 单例

### 添加实例

````
$c = new C();

Container::singleton($c);
````

### 获取单例中的实例
````
// 传入要获取单例的类名
$c = Container::getSingleton('C');
````
### 销毁实例

````
// 销毁类 C 的单例
Container::delSingleton('C');
````

### 单例注册
Container类提供一个register的方法用来注册单例。和singleton不同，register方法实现自定义类替换抽象类功能。更改实例时候，不用重写代码去获取实例
````
use IOC\Container;

// set exception handler
Container::register(
    Exceptions\ExceptionHandler::class, 
    App\Exceptions\Handler::class
);

// get singleton 这里获取的其实是 App\Exceptions\Handler 的实例
$handler = Container::getSingleton(Exceptions\ExceptionHandler::class);
````
第二个参数不传时，默认使用抽象类的实例。
````
use IOC\Container;

// set exception handler
Container::register(Exceptions\ExceptionHandler::class);

// get singleton 这里获取的是 Exceptions\ExceptionHandler 的实例
$handler = Container::getSingleton(Exceptions\ExceptionHandler::class);
````
