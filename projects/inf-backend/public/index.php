<?php

class HelloWorld
{
    public int $x;
    protected string $y;
    private float $z;

    public static array $xxx = ['test', 'test2'];

    const SOME_CONST = 'some const value';

    public function __construct($x, $y, $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        
    }

    public function setZ(float $z)
    {
        $this->z = $z;
        return $this; // zwraca instancję klasy, aby umożliwić łańcuchowe wywoływanie metod
    }

    public function getZ()
    {
        return $this->z;
    }

    public function sayHello()
    {
        return "Hello, World!";
    }
}

class HelloWorldSecond extends HelloWorld
{
    public HelloWorld $helloWorld;

}

$helloWorld = new HelloWorld(1, 'test', 1.5);
echo $helloWorld->sayHello();
$helloWorld->setZ(2.5)->sayHello(); // łańcuchowe wywoływanie metod
echo $helloWorld->getZ(); // zwraca 3.5

echo HelloWorld::SOME_CONST; // dostęp do stałej klasy
echo HelloWorld::$xxx[0]; // dostęp do statycznej właściwości klasy