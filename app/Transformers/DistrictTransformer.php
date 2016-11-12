<?php

namespace App\Transformers;

use App\District;
use League\Fractal\TransformerAbstract;

class DistrictTransformer extends TransformerAbstract
{
	public function transform(District $district)
	{
		return [
			'id' => (int) $district->id,
			'name' => $district->name
		];
	}
}
