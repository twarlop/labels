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

	public function __construct($handelaar_id, $connection)
	{
		$this->handelaar_id = $handelaar_id;
		$this->connection = $connection;
	}
	
	public function fetchProdids()
	{
		$queue = $this->connection->table('handelaars_labels_queue')->whereHandelaar_id($this->handelaar_id)->get(array('product_id'));
		$prodids = array_map(function($item){
			return $item['product_id'];
		}, $queue);
		return array_values($prodids);
	}

	public function queue($prodid)
	{
		$query = $this->connection->table('handelaars_labels_queue');
		$query->insert(array(
			'handelaar_id' => $this->handelaar_id,
			'product_id' => $prodid
		));
	}

	public function dequeue($prodid)
	{
		$query = $this->connection->table('handelaars_labels_queue');
		$query->where('handelaar_id', $this->handelaar_id)
			->where('product_id', $prodid)
			->delete();
	}

	public function clear()
	{
		$query = $this->connection->table('handelaars_labels_queue');
		$query->where('handelaar_id', $this->handelaar_id)
			->delete();
	}

}