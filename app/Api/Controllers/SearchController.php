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

        $arrSearch = explode(' ', $request->input('q'));
        $personCollection = new \Illuminate\Database\Eloquent\Collection;

        foreach ($arrSearch as $search) {
            $search  = '%'.$search.'%';
            $persons = Person::where('firstname', 'LIKE', $search)
                         ->orWhere('lastname', 'LIKE', $search)
                         ->orWhere('peerage', 'LIKE', $search)
                         ->orWhere('title', 'LIKE', $search)
                         ->orWhere('profession', 'LIKE', $search)
                         ->get();
            $personCollection = $personCollection->merge($persons);
        }

        $personCollection = $personCollection->keyBy('id');
        $personCollection->each(function ($person) use (&$personCollection, $request)  {
            if (strtolower($person->firstname.' '.trim($person->peerage.' '.$person->lastname)) == strtolower($request->input('q'))) {
                $personCollection->forget($person->id);
                $personCollection->prepend($person);
            }
        });

        $factionCollection = new \Illuminate\Database\Eloquent\Collection;

        foreach ($arrSearch as $search) {
            $search = '%'.$search.'%';
            $factions = Faction::where('name', 'LIKE', $search)->get();
            $factionCollection = $factionCollection->merge($factions);
        }

        $data = [];

        foreach ($factionCollection as $faction) {
            $data[] = [
                'url' => '/#/faction/'.$faction->id,
                'caption' => 'Fraktion '.$faction->name
            ];
        }

        foreach ($personCollection as $person) {
            $data[] = [
                'url' => '/#/person/'.$person->id,
                'caption' => $person->firstname.' '.trim($person->peerage.' '.$person->lastname).' ('.$person->faction->name.')'
            ];
        }

        return $data;
    }
}
