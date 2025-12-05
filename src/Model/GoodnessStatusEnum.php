<?php 

namespace App\Model;

enum GoodnessStatusEnum: int
{
    case Active = 1;
    case Proposal = 2;
    case Deleted = 9;
}