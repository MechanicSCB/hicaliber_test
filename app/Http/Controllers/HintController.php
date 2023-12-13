<?php

namespace App\Http\Controllers;

use App\Classes\Search\Pgsql\PgsqlSearchHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HintController extends Controller
{
    public function getHints(Request $request): array
    {
        $limit = 5;
        $searchString = $request['search'] ?? '';
        $head = null;

        if(str_contains($searchString, ' ')){
            $head = Str::beforeLast($searchString, ' ');
            $searchString = Str::afterLast($searchString, ' ');
        }

        $hints = (new PgsqlSearchHandler())->getHintsForWord($searchString, $limit);

        return array_map(fn($v) => ['value' => $head ? "$head $v" : $v], $hints);
    }
}
