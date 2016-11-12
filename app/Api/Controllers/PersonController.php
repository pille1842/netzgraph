<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Transformers\PersonTransformer;

class PersonController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $persons = Person::all();

        return $this->response->collection($persons, new PersonTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $person = Person::findOrFail($id);

        return $this->response->item($person, new PersonTransformer);
    }
}
