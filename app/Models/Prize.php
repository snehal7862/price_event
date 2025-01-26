<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'probability', 'awarded_count'];

    public static function nextPrize($totalPrizes)
    {
        $probabilities = Prize::pluck('probability', 'title');
        
        $prizes = [];
        foreach ($probabilities as $prize => $probability) {
            $prizes[$prize] = round(($probability / 100) * $totalPrizes);
        }
        
        static $prizesLeft = null;
        static $initialized = false;
        
        if (!$initialized) {
            $prizesLeft = $prizes;
            $initialized = true;
        }
        
        foreach ($prizesLeft as $prize => $remaining) {
            if ($remaining > 0) {
                $prizesLeft[$prize]--;
                
                \App\Models\Prize::where('title', $prize)->increment('awarded_count');

                return $prize;
            }
        }
    }

}
