<?php


namespace App\Classes\Search\Pgsql;


use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PgsqlSearchHandler
{

    public function getSimilarWords(string $searchString, int $limit = 5): array
    {
        $field = 'word';
        $hints = [];

        foreach (explode(' ', $searchString) as $word) {
            //$hints =[...$hints, ...$this->getHintsForWord($word, $limit)];
            $hints[$word] = $this->getHintsForWord($word, $limit);
        }

        return $hints;
    }

    public function getHintsForWord(string $string, int $limit = 5): array
    {
        $field = 'word';

        $hints = DB::table('words')
            ->selectRaw("$field, strict_word_similarity('$string', $field) AS sml")
            ->whereRaw("'$string' % $field")
            ->orderByDesc('sml')
            ->take($limit)
            ->pluck($field)
            ->toArray();

        if (in_array($string, $hints)) {
            // return [];
        }

        return $hints;
    }

}
