<?php

namespace ProductLabels\Migration;

use ProductLabels\DB;
use Illuminate\Database\Schema\Builder;
use ProductLabels\Contract\MigrationInterface;
use DBRM;

/**
* Base
*/
abstract class Base implements MigrationInterface
{
	protected $builder;

	protected $connection;

	public function __construct()
	{
		$connection = DBRM::connection();
		$this->connection = $connection;
		$this->builder = $connection->getSchemaBuilder();
	}
}