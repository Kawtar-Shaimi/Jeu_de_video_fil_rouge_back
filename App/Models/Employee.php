<?php
namespace App\Models;

use JsonSerializable;
class Employee implements JsonSerializable
{
    private $id;
    private $name;
    private $email;
    private $photo;
    private $salary;
    private $password;

    public function __construct($id, $name, $salary, $email, $password, $photo = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->salary = $salary;
        $this->email = $email;
        $this->photo = $photo;
        $this->password = $password;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'salary' => $this->salary,
            'photo' => $this->photo,
        ];
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function setSalary($salary)
    {
        $this->salary = $salary;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}
