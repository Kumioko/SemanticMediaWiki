<?php

namespace SMW\Tests\Elastic;

use SMW\Elastic\ElasticFactory;

/**
 * @covers \SMW\Elastic\ElasticFactory
 * @group semantic-mediawiki
 *
 * @license GNU GPL v2+
 * @since 3.0
 *
 * @author mwjames
 */
class ElasticFactoryTest extends \PHPUnit_Framework_TestCase {

	private $store;
	private $outputFormatter;
	private $conditionBuilder;

	protected function setUp() {

		$this->store = $this->getMockBuilder( '\SMW\Store' )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$this->outputFormatter = $this->getMockBuilder( '\SMW\MediaWiki\Specials\Admin\OutputFormatter' )
			->disableOriginalConstructor()
			->getMock();

		$this->conditionBuilder = $this->getMockBuilder( '\SMW\Elastic\QueryEngine\ConditionBuilder' )
			->disableOriginalConstructor()
			->getMock();
	}

	public function testCanConstruct() {

		$this->assertInstanceOf(
			ElasticFactory::class,
			new ElasticFactory()
		);
	}

	public function testCanConstructConfig() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\Config',
			$instance->newConfig()
		);
	}

	public function testCanConstructConnectionProvider() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\Connection\ConnectionProvider',
			$instance->newConnectionProvider()
		);
	}

	public function testCanConstructIndexer() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\Indexer\Indexer',
			$instance->newIndexer( $this->store )
		);
	}

	public function testCanConstructQueryEngine() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\QueryEngine',
			$instance->newQueryEngine( $this->store )
		);
	}

	public function testCanConstructRebuilder() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\Indexer\Rebuilder',
			$instance->newRebuilder( $this->store )
		);
	}

	public function testCanConstructInfoTaskHandler() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\Admin\ElasticClientTaskHandler',
			$instance->newInfoTaskHandler( $this->store, $this->outputFormatter )
		);
	}

	public function testCanConstructConceptDescriptionInterpreter() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\QueryEngine\DescriptionInterpreters\ConceptDescriptionInterpreter',
			$instance->newConceptDescriptionInterpreter( $this->conditionBuilder )
		);
	}

	public function testCanConstructSomePropertyInterpreter() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\QueryEngine\DescriptionInterpreters\SomePropertyInterpreter',
			$instance->newSomePropertyInterpreter( $this->conditionBuilder )
		);
	}

	public function testCanConstructClassDescriptionInterpreter() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\QueryEngine\DescriptionInterpreters\ClassDescriptionInterpreter',
			$instance->newClassDescriptionInterpreter( $this->conditionBuilder )
		);
	}

	public function testCanConstructNamespaceDescriptionInterpreter() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\QueryEngine\DescriptionInterpreters\NamespaceDescriptionInterpreter',
			$instance->newNamespaceDescriptionInterpreter( $this->conditionBuilder )
		);
	}

	public function testCanConstructValueDescriptionInterpreter() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\QueryEngine\DescriptionInterpreters\ValueDescriptionInterpreter',
			$instance->newValueDescriptionInterpreter( $this->conditionBuilder )
		);
	}

	public function testCanConstructConjunctionInterpreter() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\QueryEngine\DescriptionInterpreters\ConjunctionInterpreter',
			$instance->newConjunctionInterpreter( $this->conditionBuilder )
		);
	}

	public function testCanConstructDisjunctionInterpreter() {

		$instance = new ElasticFactory();

		$this->assertInstanceOf(
			'\SMW\Elastic\QueryEngine\DescriptionInterpreters\DisjunctionInterpreter',
			$instance->newDisjunctionInterpreter( $this->conditionBuilder )
		);
	}

}
