<?php 

namespace App\Model;

enum GoodnessTypeEnum: int 
{
    case Language = 1;
    case DataBase = 2;
    case Tool = 3;
}