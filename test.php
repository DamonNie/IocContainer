<?php
require 'Container.php';



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

$test = Container::getInstance(B::class);//, 'action',[],[Container::run(SendQQ::class,'send',[],['reflection is good']),'damon1']);
print_r($test);


echo "<br/>";
echo Container::run(B::class, 'getTotal' ,[],[10]);
$b = Container::getInstance(B::class, [10]);
print_r($b->getTotal()); // result is 20
