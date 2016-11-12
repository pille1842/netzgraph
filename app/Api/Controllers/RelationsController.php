<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Faction;

class RelationsController extends BaseController
{
    public function allpersons()
    {
        $persons = Person::all();

        $data = [];
        $ids = [];

        $object = new \stdClass();
        $object->id = 0;
        $object->label = 'Bundestag';
        $object->shape = 'circularImage';
        $object->image = 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Deutscher_Bundestag_logo.svg/690px-Deutscher_Bundestag_logo.svg.png';

        $data[] = $object;

        foreach ($persons as $person) {
        	$object = new \stdClass();
        	$object->id = $person->id;
        	$object->label = $person->firstname.' '.trim($person->peerage.' '.$person->lastname);
        	$object->shape = 'circularImage';
        	$object->image = $person->image;

        	$ids[] = $object->id;
        	$data[] = $object;
        }

        $nodes = [];
        foreach ($ids as $id) {
        	$object = new \stdClass();
        	$object->from = $id;
        	$object->to = 0;

        	$nodes[] = $object;
        }

        return ['nodes' => $data, 'edges' => $nodes];
    }
	
	public function person($id)
	{
		$person = Person::findOrFail($id);
		$committees = $person->committees;
		
		$personId = 0;
		$baseCommitteeId = 5000; # könnte auch als counter gestaltet werden der immer hochzählt so das keine große statische nummer genutzt werden muss

		
		$nodes = [];
		$edges = [];
		
		# create person node
		$object = new \stdClass();
        $object->id = $personId;
        $object->label = $person->firstname.' '.trim($person->peerage.' '.$person->lastname);
        $object->shape = 'circularImage';
        $object->image = $person->image;
		$nodes[] = $object; # add node
		
		# create committee nodes
		$object = new \stdClass();
        $object->id = $baseCommitteeId;
        $object->label = 'Gremien';
		$nodes[] = $object; # add node
		
		# create relation betweeen person and comittee
		$object = new \stdClass();
        $object->from = $personId;
        $object->to = $baseCommitteeId;
		$edges[] = $object;
		
		# create nodes for each committee
		foreach($committees as $committee) {
			$role = $committee->pivot->role;
			
			$object = new \stdClass();
        	$object->id = $committee->id;
        	$object->label = $committee->name;
			$nodes[] = $object; # add not node
			
			# create relation with base committe node
			$object = new \stdClass();
        	$object->from = $baseCommitteeId;
        	$object->to = $committee->id;
			$edges[] = $object; # add to edge
		}
		return ['nodes' => $nodes, 'edges' => $edges];
	}

    public function faction($id)
    {
    	$faction = Faction::findOrFail($id);

    	$persons = $faction->persons;

    	$data = [];
        $ids = [];

        $object = new \stdClass();
        $object->id = 0;
        $object->label = $faction->name;
        $object->shape = 'circularImage';
        $object->image = $faction->image;

        $data[] = $object;

        foreach ($persons as $person) {
        	$object = new \stdClass();
        	$object->id = $person->id;
        	$object->label = $person->firstname.' '.trim($person->peerage.' '.$person->lastname);
        	$object->shape = 'circularImage';
        	$object->image = $person->image;

        	$ids[] = $object->id;
        	$data[] = $object;
        }

        $nodes = [];
        foreach ($ids as $id) {
        	$object = new \stdClass();
        	$object->from = $id;
        	$object->to = 0;

        	$nodes[] = $object;
        }

        return ['nodes' => $data, 'edges' => $nodes];
    }

    public function factions()
    {
        $factions = Faction::all();

        $object = new \stdClass();
        $object->id = 0;
        $object->label = 'Bundestag';
        $object->shape = 'circularImage';
        $object->image = 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Deutscher_Bundestag_logo.svg/690px-Deutscher_Bundestag_logo.svg.png';

        $data[] = $object;

        foreach ($factions as $faction) {
            $object = new \stdClass();
            $object->id = $faction->id;
            $object->label = $faction->name;
            $object->shape = 'circularImage';
            $object->image = $faction->image;

            $ids[] = $object->id;
            $data[] = $object;
        }

        $nodes = [];
        foreach ($ids as $id) {
            $object = new \stdClass();
            $object->from = $id;
            $object->to = 0;

            $nodes[] = $object;
        }

        return ['nodes' => $data, 'edges' => $nodes];
    }
}
