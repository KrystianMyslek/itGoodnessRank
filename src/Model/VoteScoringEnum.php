<?php 

namespace App\Model;

enum VoteScoringEnum: int
{
    case Gold = 5;
    case Silver = 3;
    case Bronze = 1;

    public static function fromName(string $name): VoteScoringEnum
    {
        foreach (self::cases() as $score) {
            if ($name === $score->name) {
                return $score;
            }
        }
        
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }
}