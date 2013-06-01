<?php

namespace ProductLabels\Migration;

use ProductLabels\DB;
use Illuminate\Database\Schema\Builder;

/**
* Base
*/
class Base
{
	protected $builder;

	protected $connection;

	public function __construct()
	{
		$connection = DB::connection('sos');
		$this->connection = $connection;
		$this->builder = $connection->getSchemaBuilder();
	}
}