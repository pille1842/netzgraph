<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Person;

class RelationsController extends BaseController
{
    public function allpersons()
    {
        $persons = Person::all();

        $data = [];
        $ids = [];

        foreach ($persons as $person) {
        	$object = new \stdClass();
        	$object->id = $person->id;
        	$object->label = $person->firstname.' '.$person->lastname;
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

        return ['data' => $data, 'nodes' => $nodes];
    }
}
