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
		
		$committeeIdCounter = 1000;
		
		$personId = 0;
		$baseCommitteeId = 1; # könnte auch als counter gestaltet werden der immer hochzählt so das keine große statische nummer genutzt werden muss
		$OrdentlichesMitgliedId = 2;
		$StellvertretendesMitgliedId = 3;
		
		$baseEarningLevelNode = 4;
		$earningLevelIdCounter = 5000; # from 5000 - 5009 if all earning levels are used
		$earningNameCounter = 5010; # from 5010 - 6000
		
		$stateId = 6001;
		$districtId = 6002;
		$fractionId = 6003;
		
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
        $object->from = $baseCommitteeId;
        $object->to = $personId;
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
        	$object->id = $committeeIdCounter;
        	$object->label = $committee->name;
			$nodes[] = $object; # add not node
			
			# check wich role
			if($role == "Stellvertretendes Mitglied") {
				# stellvertretendes committe
				# create relation with base committe node
				$object = new \stdClass();
				$object->from = $committeeIdCounter;
				$object->to = $StellvertretendesMitgliedId;
				$edges[] = $object; # add to edge
				
			} else {
				# Ordentliches Mitglied
				# create relation with base committe node
				$object = new \stdClass();
				$object->from = $committeeIdCounter;
				$object->to = $OrdentlichesMitgliedId;
				$edges[] = $object; # add to edge
			}
			
			$committeeIdCounter = $committeeIdCounter + 1;
		}
		
		# ---------------------- earning levels and earnings -------------------------------------
		# ids at top
		 
		# create base earning level node
		$object = new \stdClass();
        $object->id = $baseEarningLevelNode;
        $object->label = 'Nebeneinkünfte';
		$nodes[] = $object; # add node
		
		# create relation from person to base earning level node
		$object = new \stdClass();
		$object->from = $baseEarningLevelNode;
		$object->to = $personId;
		$edges[] = $object; # add to edge
 
		
		$earnings = $person->earnings;
		$arrEarningNodes = [];		
		foreach ($earnings as $earning) {
			$arrEarningNodes[$earning->earninglevel->value][] = $earning->name;
		}
		
		foreach($arrEarningNodes as $earninglevelName => $earningNames) {
			
			# create earning level node
			$object = new \stdClass();
			$object->id = $earningLevelIdCounter;
			$object->label = $earninglevelName;
			$nodes[] = $object; # add node
			
			# create relation to base earning level node
			$object = new \stdClass();
			$object->from = $earningLevelIdCounter;
			$object->to = $baseEarningLevelNode;
			$edges[] = $object; # add to edge
			
			# go through all names that are in the same earning level
			foreach($earningNames as $earningName) {
				# add earning name node
				$object = new \stdClass();
				$object->id = $earningNameCounter;
				$object->label = $earningName;
				$nodes[] = $object; # add node
				
				# add relation to earning level
				$object = new \stdClass();
				$object->from = $earningNameCounter;
				$object->to = $earningLevelIdCounter;
				$edges[] = $object; # add to edge
				
				# add 1 to the counter
				$earningNameCounter = $earningNameCounter + 1;
			}
			
			# add 1 to the counter
			$earningLevelIdCounter = $earningLevelIdCounter + 1;
		}
		
		
		# ----- add state ---------
		
		# add bundesland node
		
		# id at top
		$state = $person->state;
	
		# create state node
		$object = new \stdClass();
		$object->id = $stateId;
		$object->label = $state->name;
		$object->shape = 'circularImage';
        $object->image = $state->image;
		$nodes[] = $object; # add node
		
		# add relation to person
		$object = new \stdClass();
		$object->from = $personId;
		$object->to = $stateId;
		$edges[] = $object; # add to edge
		
		
		# ----- add districts ----------
		
		# add district node
		
		# id at top
		$district = $person->district;
		
		if($district->name == "NULL") {
			# create district node
			$object = new \stdClass();
			$object->id = $districtId;
			$object->label = "Kein Wahlkreis";
			$nodes[] = $object; # add node
		} else {
			# create district node
			$object = new \stdClass();
			$object->id = $districtId;
			$object->label = $district->name;
			$nodes[] = $object; # add node
		}
		
		# add relation to person
		$object = new \stdClass();
		$object->from = $personId;
		$object->to = $districtId;
		$edges[] = $object; # add to edge
		
		
		# ----- add fraction ----------
		# id at top
		
		*/
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
            $object->shape = 'image';
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

        $options = [
            'physics' => [
                'barnesHut' => [
                    'gravitationalConstant' => -10000
                ]
            ]
        ];

        return ['nodes' => $data, 'edges' => $nodes, 'options' => array_merge($this->defaultOptions, $options)];
    }
}
