<?php

namespace App\Transformers;

use App\Person;
use League\Fractal\TransformerAbstract;

class PersonTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [
		'committees',
		'district',
		'earnings',
		'faction',
		'state'
	];

	public function transform(Person $person)
	{
		return [
			'id' => (int) $person->id,
			'mdb_id' => (int) $person->mdb_id,
			'firstname' => $person->firstname,
			'lastname' => $person->lastname,
			'gender' => $person->gender,
			'profession' => $person->profession,
			'title' => $person->title,
			'peerage' => $person->peerage,
			'religion' => $person->religion,
			'birthday' => $person->birthday,
			'image' => $person->image,
			'twitter' => $person->twitter
		];
	}

	public function includeCommittees(Person $person)
	{
		$committees = $person->committees;

		return $this->collection($committees, new CommitteeTransformer);
	}

	public function includeDistrict(Person $person)
	{
		$district = $person->district;

		return $this->item($district, new DistrictTransformer);
	}

	public function includeEarnings(Person $person)
	{
		$earnings = $person->earnings;

		return $this->collection($earnings, new EarningTransformer);
	}

	public function includeFaction(Person $person)
	{
		$faction = $person->faction;

		return $this->item($faction, new FactionTransformer);
	}

	public function includeState(Person $person)
	{
		$state = $person->state;

		return $this->item($state, new StateTransformer);
	}
}
