<?php

interface MigrationInterface{

	public function run();

	protected function init();

}