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
        static $prizesLeft = null;
        
        if ($prizesLeft === null) {
            $prizesLeft = collect($probabilities)->mapWithKeys(function ($probability, $prize) use ($totalPrizes) {
                return [$prize => round(($probability / 100) * $totalPrizes)];
            })->toArray();
        }
        
        foreach ($prizesLeft as $prize => $remaining) {
            if ($remaining > 0) {
                $prizesLeft[$prize]--;
                
                Prize::where('title', $prize)->increment('awarded_count');

                return $prize;
            }
        }
        
        return null;
    }


}
