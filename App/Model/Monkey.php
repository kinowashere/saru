<?php


namespace App\Model;


use Saru\ORM\Model;

class Monkey extends Model
{
    protected array $properties = ["id", "name", "species", "mood"];
    protected array $protected = ["mood"];
}