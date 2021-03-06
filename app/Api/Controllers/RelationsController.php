<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Faction;
use App\State;
use App\Earninglevel;
use App\Committee;

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
            'color' => 'lightgray',
            'length' => 100
        ],
        'clickToUse' => false,
        'layout' => [
            'improvedLayout' => true,
            'randomSeed' => 2,
			'hierarchical' => [
				'enabled'=> false
			]
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
		
		$stellvertretenderCommitteeIdCounter = 1000;
		$ordentlicherCommitteeIdCounter = 2000;
		
		$fractionId = 0;
		$personId = 1;
		$baseCommitteeId = 2; # könnte auch als counter gestaltet werden der immer hochzählt so das keine große statische nummer genutzt werden muss
		$OrdentlichesMitgliedId = 3;
		$StellvertretendesMitgliedId = 4;
		
		$baseEarningLevelNode = 5;
		$earningLevelIdCounter = 5000; # from 5000 - 5009 if all earning levels are used
		$earningNameCounter = 5011; # from 5010 - 6000
		
		$baseStateId = 6000;
		$stateId = 6001;
		$baseDistrictId = 6002;
		$districtId = 6003;
		
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
		
		$ordentlicherCounter = 0;
		$stellvertrentenderCounter = 0;
		# create nodes for each committee
		foreach($committees as $committee) {
			$role = $committee->pivot->role;
			
						# check wich role
			if($role == "Stellvertretendes Mitglied") {
				$object = new \stdClass();
				$object->id = $stellvertretenderCommitteeIdCounter;
				$object->label = $committee->name;
                $object->url = '/api/relations/committee/'.$committee->id;
				$nodes[] = $object; # add not node
			} else {
				$object = new \stdClass();
				$object->id = $ordentlicherCommitteeIdCounter;
				$object->label = $committee->name;
                $object->url = '/api/relations/committee/'.$committee->id;
				$nodes[] = $object; # add not node
			}
			
			# check wich role
			if($role == "Stellvertretendes Mitglied") {
				if($stellvertrentenderCounter < 1) {
					# stellvertretendes committe
					# create relation with base committe node
					$object = new \stdClass();
					$object->from = $stellvertretenderCommitteeIdCounter;
					$object->to = $StellvertretendesMitgliedId;
					$edges[] = $object; # add to edge
				} else {
					$object = new \stdClass();
					$object->from = $stellvertretenderCommitteeIdCounter;
					$object->to = $stellvertretenderCommitteeIdCounter-1;
					$edges[] = $object; # add to edge
				}
				$stellvertrentenderCounter = $stellvertrentenderCounter + 1;
				$stellvertretenderCommitteeIdCounter = $stellvertretenderCommitteeIdCounter + 1;
				
			} else {
				if($ordentlicherCounter < 1) {
					# Ordentliches Mitglied
					# create relation with base committe node
					$object = new \stdClass();
					$object->from = $ordentlicherCommitteeIdCounter;
					$object->to = $OrdentlichesMitgliedId;
					$edges[] = $object; # add to edge
				} else {
					$object = new \stdClass();
					$object->from = $ordentlicherCommitteeIdCounter;
					$object->to = $ordentlicherCommitteeIdCounter-1;
					$edges[] = $object; # add to edge
				}
				$ordentlicherCounter = $ordentlicherCounter + 1;
				$ordentlicherCommitteeIdCounter = $ordentlicherCommitteeIdCounter + 1;
			}
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
			$counter = 0;
			foreach($earningNames as $earningName) {
				# add earning name node
				$object = new \stdClass();
				$object->id = $earningNameCounter;
				$object->label = $earningName;
				$nodes[] = $object; # add node
				
				if($counter < 1) {
					# add relation to earning level
				$object = new \stdClass();
				$object->from = $earningNameCounter;
				$object->to = $earningLevelIdCounter;
				$edges[] = $object; # add to edge
				} else {
					# add relation to earning level
				$object = new \stdClass();
				$object->from = $earningNameCounter;
				$object->to = $earningNameCounter-1;
				$edges[] = $object; # add to edge
				}
				$counter = $counter + 1;
				
				$earningNameCounter = $earningNameCounter+1;
			}
			# add 1 to the counter
			$earningLevelIdCounter = $earningLevelIdCounter + 1;
		}
		
		
		# ----- add state ---------
		
		# add base bundesland node
		$object = new \stdClass();
		$object->id = $baseStateId;
		$object->label = "Bundesland";
		$nodes[] = $object; # add node
		
		# add relation to person
		$object = new \stdClass();
		$object->from = $baseStateId;
		$object->to = $personId;
		$edges[] = $object; # add to edge
		
		$state = $person->state;
	
		# create state node
		$object = new \stdClass();
		$object->id = $stateId;
		$object->label = $state->name;
		$object->shape = 'image';
        $object->image = $state->image;
        $object->url = '/api/relations/state/'.$state->id;
		$nodes[] = $object; # add node
		
		# add relation to person
		$object = new \stdClass();
		$object->from = $stateId;
		$object->to = $baseStateId;
		$edges[] = $object; # add to edge
		
		
		# ----- add districts ----------
		
		# add base district node
		$object = new \stdClass();
		$object->id = $baseDistrictId;
		$object->label = "Wahlkreis";
		$nodes[] = $object; # add node
		
		# add relation to person
		$object = new \stdClass();
		$object->from = $baseDistrictId;
		$object->to = $personId;
		$edges[] = $object; # add to edge
		
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
		$object->from = $districtId;
		$object->to = $baseDistrictId;
		$edges[] = $object; # add to edge
		
		
		# ----- add faction ----------
		# add fraction node
		$faction = $person->faction;
		
		$object = new \stdClass();
		$object->id = $fractionId;
		$object->shape = 'image';
        $object->image = $faction->image;
        $object->url = '/api/relations/faction/'.$faction->id;
		$nodes[] = $object; # add node
		
		# add relation to person
		$object = new \stdClass();
		$object->from = $personId;
		$object->to = $fractionId;
		$edges[] = $object; # add to edge
		
		$options = [
            'layout' => [
                'hierarchical' => [
					'enabled'=> true,
					'levelSeparation' => 150,
					'nodeSpacing' => 1000,
					'treeSpacing' => 200,
					'blockShifting' => true,
					'edgeMinimization' => true,
					'parentCentralization' => true,
                    'direction' => "DU",
					'sortMethod' => "directed"
                ]
            ]
        ];
		
		//font: '24px'
		return ['nodes' => $nodes, 'edges' => $edges, 'options' => array_replace($this->defaultOptions, $options)];
	}

    public function faction($id)
    {
    	$faction = Faction::findOrFail($id);

    	$persons = $faction->persons;

    	$data = [];
        $ids = [];

        $object = new \stdClass();
        $object->id = 0;
        $object->shape = 'image';
        $object->image = $faction->image;
		$object->title = "<strong>".count($persons)." Abgeordnete</strong>";

        $data[] = $object;

        foreach ($persons as $person) {
        	$object = new \stdClass();
        	$object->id = $person->id;
        	$object->label = $person->firstname.' '.trim($person->peerage.' '.$person->lastname);
        	$object->shape = 'circularImage';
        	$object->image = $person->image;
            $object->url = '/api/relations/person/'.$person->id;

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
			'layout' => [
				'improvedLayout' => false,
				'hierarchical' => [
					'enabled'=> false
				]
			],
			'physics' => [
				'minVelocity' => 9,
				'solver' => 'barnesHut',
				'timestep' => 0.2,
				'adaptiveTimestep' => true,
				'barnesHut' => [
                    'gravitationalConstant' => -20000
                ]
			]
        ];

        return ['nodes' => $data, 'edges' => $nodes, 'options' => array_replace($this->defaultOptions, $options)];
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
            $object->url = '/api/relations/faction/'.$faction->id;

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

        return ['nodes' => $data, 'edges' => $nodes, 'options' => array_replace($this->defaultOptions, $options)];
    }

    public function state($id)
    {
        $state = State::findOrFail($id);
        $persons = $state->persons;

        $object = new \stdClass();
        $object->id = 0;
        $object->label = $state->name;
        $object->shape = 'image';
        $object->image = $state->image;
		$object->title = "<strong>".count($persons)." Abgeordnete</strong>";

        $data[] = $object;

        foreach ($persons as $person) {
            $object = new \stdClass();
            $object->id = $person->id;
            $object->shape = 'circularImage';
            $object->image = $person->image;
            $object->label = $person->firstname.' '.trim($person->peerage.' '.$person->lastname);
            $object->url = '/api/relations/person/'.$person->id;

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

        return ['nodes' => $data, 'edges' => $nodes, 'options' => array_replace($this->defaultOptions, $options)];
    }

    public function factionProfession($id)
    {
        $faction = Faction::findOrFail($id);

        $professions = \DB::table('persons')
                       ->select(\DB::raw("COUNT(*) AS prof_count, profession"))
                       ->where('faction_id', '=', $id)
                       ->groupBy('profession')
                       ->orderBy('prof_count', 'DESC')
                       ->limit(10)
                       ->get();

        $nodes = [];
        $edges = [];

        $object = new \stdClass();
        $object->id = $id;
        $object->shape = 'image';
        $object->image = $faction->image;
        $object->url = '/api/relations/faction/'.$faction->id;

        $nodes[] = $object;
        $professionCounter = 1000;
        $profMax = 0;
        $profMin = 1000;

        foreach ($professions as $profession) {
            $object = new \stdClass();
            $object->id = $professionCounter;
            $object->label = $profession->profession;
			$object->title = "<strong>".$profession->prof_count."</strong>";
			$nodes[] = $object;

            $object = new \stdClass();
            $object->from = $faction->id;
            $object->to = $professionCounter;
            $object->value = $profession->prof_count;
            $object->title = "<strong>".$profession->prof_count."</strong>";

            $edges[] = $object;

            $professionCounter++;

            if ($profession->prof_count > $profMax) {
                $profMax = $profession->prof_count;
            }
            if ($profession->prof_count < $profMin) {
                $profMin = $profession->prof_count;
            }
        }

        $options = ['edges' => ['length' => 250]];

        return ['nodes' => $nodes, 'edges' => $edges, 'options' => array_replace($this->defaultOptions, $options)];
    }

    public function factionEarnings($id)
    {
        $faction = Faction::findOrFail($id);

        $earnings = \DB::table('earnings')
                  ->select(\DB::raw("COUNT(*) AS earn_count, earninglevel_id"))
                  ->join('persons', function ($join) use ($id) {
                    $join->on('earnings.person_id', '=', 'persons.id')
                         ->where('persons.faction_id', '=', $id);
                  })
                  ->groupBy('earninglevel_id')
                  ->orderBy('earn_count', 'DESC')
                  ->get();

        $earninglevels = Earninglevel::all()->keyBy('id');

        $nodes = [];
        $edges = [];

        $earningTotals = 0;

        foreach ($earnings as $earning) {
            $earningTotals += $earning->earn_count;
        }

        $object = new \stdClass();
        $object->id = $id;
        $object->shape = 'image';
        $object->image = $faction->image;
        $object->url = '/api/relations/faction/'.$faction->id;
        $object->title = '<strong>Insgesamt '.$earningTotals.' Nebeneinkünfte</strong>';

        $nodes[] = $object;
        $earningCounter = 1000;
        $earnMax = 0;
        $earnMin = 1000;

        foreach ($earnings as $earning) {
            $object = new \stdClass();
            $object->id = $earningCounter;
            $object->label = $earninglevels->find($earning->earninglevel_id)->value;
            $object->title = "<strong>".$earning->earn_count." Quellen</strong>";
            $nodes[] = $object;

            $object = new \stdClass();
            $object->from = $faction->id;
            $object->to = $earningCounter;
            $object->value = $earning->earn_count;
            $object->title = "<strong>".$earning->earn_count." Quellen</strong>";

            $edges[] = $object;

            $earningCounter++;

            if ($earning->earn_count > $earnMax) {
                $earnMax = $earning->earn_count;
            }
            if ($earning->earn_count < $earnMin) {
                $earnMin = $earning->earn_count;
            }
        }

        $options = ['edges' => ['length' => 250]];

        return ['nodes' => $nodes, 'edges' => $edges, 'options' => array_replace($this->defaultOptions, $options)];
    }

    public function committee($id)
    {
        $committee = Committee::findOrFail($id);

        $persons = $committee->persons;

        $object = new \stdClass();
        $object->id = 0;
        $object->label = $committee->name;
        $object->title = "<strong>".count($persons)." Abgeordnete</strong>";

        $data[] = $object;

        foreach ($persons as $person) {
            $object = new \stdClass();
            $object->id = $person->id;
            $object->shape = 'circularImage';
            $object->image = $person->image;
            $object->label = $person->firstname.' '.trim($person->peerage.' '.$person->lastname);
            $object->url = '/api/relations/person/'.$person->id;

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

        return ['nodes' => $data, 'edges' => $nodes, 'options' => array_replace($this->defaultOptions, $options)];
    }
}
