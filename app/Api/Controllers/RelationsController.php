<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Faction;

class RelationsController extends BaseController
{
    protected $defaultOptions = [
        'autoResize' => true,
        'height' => '100%',
        'width' => '100%',
        'locale' => 'de',
        'nodes' => [
            'borderWidth' => 4,
            'size' => 30,
            'color' => [
                'border' => 'lightgray'
            ]
        ],
        'edges' => [
            'color' => 'lightgray'
        ],
        'clickToUse' => false,
        'layout' => [
            'improvedLayout' => true,
            'randomSeed' => 2
        ]
    ];

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

        return ['nodes' => $data, 'edges' => $nodes, 'options' => $this->defaultOptions];
    }
	
	public function person($id)
	{
		$person = Person::findOrFail($id);
		$committees = $person->committees;
		
		$personId = 0;
		$baseCommitteeId = 5000; # könnte auch als counter gestaltet werden der immer hochzählt so das keine große statische nummer genutzt werden muss
		$OrdentlichesMitgliedId = 5001;
		$StellvertretendesMitgliedId = 5002;
		
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
		
		# create Ordentliches Mitglied Node
		$object = new \stdClass();
        $object->id = $OrdentlichesMitgliedId;
        $object->label = 'Ordentliches Mitglied';
		$nodes[] = $object; # add node
		
		# create Stellvertretendes Mitglied Node
		$object = new \stdClass();
        $object->id = $StellvertretendesMitgliedId;
        $object->label = 'Stellvertrendes Mitglied';
		$nodes[] = $object; # add node
		
		# insert relation from Stellvertretendes Mitglied to base commmitte
		$object = new \stdClass();
		$object->from = $StellvertretendesMitgliedId;
		$object->to = $baseCommitteeId;
		$edges[] = $object; # add to edge
		
		# insert relation from Ordentliches Mitglied to base committe
		$object = new \stdClass();
		$object->from = $OrdentlichesMitgliedId;
		$object->to = $baseCommitteeId;
		$edges[] = $object; # add to edge
		
		
		# create nodes for each committee
		foreach($committees as $committee) {
			$role = $committee->pivot->role;
			
			$object = new \stdClass();
        	$object->id = $committee->id;
        	$object->label = $committee->name;
			$nodes[] = $object; # add not node
			
			# check wich role
			if($role == "Stellvertretendes Mitglied") {
				# stellvertretendes committe
				# create relation with base committe node
				$object = new \stdClass();
				$object->from = $StellvertretendesMitgliedId;
				$object->to = $committee->id;
				$edges[] = $object; # add to edge
				
			} else {
				# Ordentliches Mitglied
				# create relation with base committe node
				$object = new \stdClass();
				$object->from = $OrdentlichesMitgliedId;
				$object->to = $committee->id;
				$edges[] = $object; # add to edge
			}
		}
		return ['nodes' => $nodes, 'edges' => $edges, 'options' => $this->defaultOptions];
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

        return ['nodes' => $data, 'edges' => $nodes, 'options' => $this->defaultOptions];
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

        return ['nodes' => $data, 'edges' => $nodes, 'options' => $this->defaultOptions];
    }
}
