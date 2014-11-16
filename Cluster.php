<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/NoSQL-Project/Cluster/Node.php');
require_once(__ROOT__.'/NoSQL-Project/Exception/ClusterException.php');

class Cluster {

	/**
	 * @var array
	 */
	private $nodes;

	/**
	 * @param array $nodes
	 */
	public function __construct(array $nodes = []) {
		$this->nodes = $nodes;
	}

	/**
	 * @param string $host
	 */
	public function appendNode($host) {
		$this->nodes[] = $host;
	}

	/**
	 * @return Node
	 * @throws Exception\ClusterException
	 */
	public function getRandomNode() {
		if (empty($this->nodes)) throw new ClusterException('Node list is empty.');
		$nodeKey = array_rand($this->nodes);
		$node = $this->nodes[$nodeKey];
		try {
			if ((array)$node === $node) {
				$node = new Node($nodeKey, $node);
				unset($this->nodes[$nodeKey]);
			} else {
				$node = new Node($node);
				unset($this->nodes[$nodeKey]);
			}
		} catch (\InvalidArgumentException $e) {
			trigger_error($e->getMessage());
		}

		return $node;
	}
}