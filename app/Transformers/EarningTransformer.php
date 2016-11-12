<?php

namespace App\Transformers;

use App\Earning;
use League\Fractal\TransformerAbstract;

class EarningTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [
		'earninglevel'
	];

	public function transform(Earning $earning)
	{
		return [
			'person_id' => (int) $earning->person_id,
			'name' => $earning->name
		];
	}

	public function includeEarninglevel(Earning $earning)
	{
		$earninglevel = $earning->earninglevel;

		return $this->item($earninglevel, new EarninglevelTransformer);
	}
}
