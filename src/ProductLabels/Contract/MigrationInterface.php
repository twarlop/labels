<?php
namespace ProductLabels\Contract;

interface MigrationInterface{

	public function run();

	protected function init();

}