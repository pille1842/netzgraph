<?php

namespace App\Transformers;

use App\State;
use League\Fractal\TransformerAbstract;

class StateTransformer extends TransformerAbstract
{
	public function transform(State $state)
	{
		return [
			'id' => (int) $state->id,
			'name' => $state->name
		];
	}
}
