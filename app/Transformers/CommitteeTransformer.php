<?php

namespace App\Transformers;

use App\Committee;
use League\Fractal\TransformerAbstract;

class CommitteeTransformer extends TransformerAbstract
{
	public function transform(Committee $committee)
	{
		return [
			'id' => (int) $committee->id,
			'short' => $committee->short,
			'name' => $committee->name,
			'url' => $committee->url
		];
	}
}
