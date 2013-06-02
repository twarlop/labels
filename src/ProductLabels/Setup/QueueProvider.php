<?php
namespace ProductLabels\Setup;

use ProductLabels\Contract\ProviderInterface;
use ProductLabels\DB;
/**
* QueueProvider
*/
class QueueProvider implements ProviderInterface
{

	protected $handelaar_id;

	/**
	 * @var Illuminate\Database\Connection
	 */
	protected $connection;

	public function __construct($handelaar_id)
	{
		$this->handelaar_id = $handelaar_id;
		$this->connection = DB::connection('sos');
	}
	
	public function fetch()
	{
		$queue = $this->connection->table('handelaars_labels_queue')->whereHandelaar_id($this->handelaar_id)->get(array('product_id'));
		$prodids = array_map(function($item){
			return $item['product_id'];
		}, $queue);
		return array_values($prodids);
	}

}