<?php

namespace App\Transformers;

use App\Earninglevel;
use League\Fractal\TransformerAbstract;

class EarninglevelTransformer extends TransformerAbstract
{
	public function transform(Earninglevel $earninglevel)
	{
		return [
			'id' => (int) $earninglevel->id,
			'value' => $earninglevel->value
		];
	}
}
