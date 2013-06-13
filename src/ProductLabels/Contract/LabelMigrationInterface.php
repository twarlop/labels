<?php
namespace ProductLabels\Contract;


interface LabelMigrationInterface{

	public function layout();

	public function photo();

	public function title();

	public function text();

	public function promotion();

	public function promotionText();

	public function promotionStop();

	public function price();

	public function logoHandelaar();

	public function logoMerk();

}