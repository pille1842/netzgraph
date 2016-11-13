<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Faction;
use App\State;

class SearchController extends BaseController
{
    public function search(Request $request)
    {
        if (!$request->has('q')) {
            return $this->response->errorBadRequest();
        }

		$arrSearchTypes = explode(',', $request->input('target'));
		
		# check if its the simple or complex relation
		
		
		$personCollection = new \Illuminate\Database\Eloquent\Collection;
        $statesCollection = new \Illuminate\Database\Eloquent\Collection;
        $factionCollection = new \Illuminate\Database\Eloquent\Collection;
        $factionProfessionCollection = new \Illuminate\Database\Eloquent\Collection;
		$arrSearch = explode(' ', $request->input('q'));
		
		foreach($arrSearchTypes as $arrSearchType) {
			switch($arrSearchType) {
				case "person":
					foreach ($arrSearch as $search) {
						$search  = '%'.$search.'%';
						$persons = Person::where('firstname', 'LIKE', $search)
									 ->orWhere('lastname', 'LIKE', $search)
									 ->orWhere('peerage', 'LIKE', $search)
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
					
					break;
				case "religion":
					foreach ($arrSearch as $search) {
						$search  = '%'.$search.'%';
						$persons = Person::where('religion', 'LIKE', $search)
									 ->get();
						$personCollection = $personCollection->merge($persons);
					}
					break;
				case "profession":
				    foreach ($arrSearch as $search) {
						$search  = '%'.$search.'%';
						$persons = Person::where('profession', 'LIKE', $search)
									 ->get();
						$personCollection = $personCollection->merge($persons);
					}
					break;
				case "title":
				    foreach ($arrSearch as $search) {
						$search  = '%'.$search.'%';
						$persons = Person::where('title', 'LIKE', $search)
									 ->get();
						$personCollection = $personCollection->merge($persons);
					}
					break;
                case "state":
                    foreach ($arrSearch as $search) {
                        $search = '%'.$search.'%';
                        $states = State::where('name', 'LIKE', $search)
                                     ->get();
                        $statesCollection = $statesCollection->merge($states);
                    }
                    break;
                case "faction-profession":
                    foreach ($arrSearch as $search) {
                        $search = '%'.$search.'%';
                        $factions = Faction::where('name', 'LIKE', $search)
                                     ->get();
                        $factionProfessionCollection = $factionProfessionCollection->merge($factions);
                    }
                    break;
			}
		}

        $data = [];

        foreach ($factionCollection as $faction) {
            $data[] = [
                'url' => '/api/relations/faction/'.$faction->id,
                'caption' => 'Fraktion '.$faction->name
            ];
        }

        foreach ($factionProfessionCollection as $faction) {
            $data[] = [
                'url' => '/api/relations/faction/'.$faction->id.'/professions',
                'caption' => 'Fraktion '.$faction->name
            ];
        }

        foreach ($personCollection as $person) {
            $data[] = [
                'url' => '/api/relations/person/'.$person->id,
                'caption' => $person->firstname.' '.trim($person->peerage.' '.$person->lastname).' ('.$person->faction->name.')'
            ];
        }

        foreach ($statesCollection as $state) {
            $data[] = [
                'url' => '/api/relations/state/'.$state->id,
                'caption' => $state->name
            ];
        }

        return $data;
    }
}
