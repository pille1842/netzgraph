<?php

namespace App\Transformers;

use App\Faction;
use League\Fractal\TransformerAbstract;

class FactionTransformer extends TransformerAbstract
{
	public function transform(Faction $faction)
	{
		return [
			'id' => (int) $faction->id,
			'name' => $faction->name
		];
	}
}
