<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Faction;

class SearchController extends BaseController
{
    public function search(Request $request)
    {
        if (!$request->has('q')) {
            return $this->response->errorBadRequest();
        }

        $search = '%'.str_replace(' ', '%', $request->input('q')).'%';

        $persons = Person::where('firstname', 'LIKE', $search)
        				 ->orWhere('lastname', 'LIKE', $search)
        				 ->orWhere('peerage', 'LIKE', $search)
        				 ->orWhere('title', 'LIKE', $search)
        				 ->get();

        $factions = Faction::where('name', 'LIKE', $search)->get();

        $data = [];

        foreach ($factions as $faction) {
        	$data[] = [
        		'url' => '/#/faction/'.$faction->id,
        		'caption' => 'Fraktion '.$faction->name
        	];
        }

        foreach ($persons as $person) {
        	$data[] = [
        		'url' => '/#/person/'.$person->id,
        		'caption' => $person->firstname.' '.trim($person->peerage.' '.$person->lastname).' ('.$person->faction->name.')'
        	];
        }

        return $data;
    }
}
