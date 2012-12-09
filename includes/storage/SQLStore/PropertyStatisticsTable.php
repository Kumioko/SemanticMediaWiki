<?php

namespace SMW\SQLStore;
use DatabaseBase;
use MWException;

/**
 * Simple implementation of PropertyStatisticsTable using MediaWikis
 * database abstraction layer and a single table.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 1.9
 *
 * @ingroup SMWStore
 *
 * @license GNU GPL v2 or later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Nischay Nahata
 */
class PropertyStatisticsTable implements \SMW\Store\PropertyStatisticsStore {

	/**
	 * @since 1.9
	 * @var string
	 */
	protected $table;

	/**
	 * @since 1.9
	 * @var DatabaseBase
	 */
	protected $dbConnection;

	/**
	 * Constructor.
	 *
	 * @since 1.9
	 *
	 * @param string $table
	 * @param DatabaseBase $dbw used for both writing and reading
	 */
	public function __construct( $table, DatabaseBase $dbw ) {
		assert( is_string( $table ) );

		$this->table = $table;
		$this->dbConnection = $dbw;
	}

	/**
	 * @see PropertyStatisticsStore::addToUsageCount
	 *
	 * @since 1.9
	 *
	 * @param integer $propertyId
	 * @param integer $value
	 *
	 * @return boolean Success indicator
	 * @throws MWException
	 */
	public function addToUsageCount( $propertyId, $value ) {
		if ( !is_int( $value ) ) {
			throw new MWException( 'The value to add must be an integer' );
		}

		if ( !is_int( $propertyId ) || $propertyId <= 0 ) {
			throw new MWException( 'The property id to add must be a positive integer' );
		}

		if ( $value == 0 ) {
			return true;
		}

		return $this->dbConnection->update(
			$this->table,
			array(
				'usage_count = usage_count ' . ( $value > 0 ? '+ ' : '- ' ) . $this->dbConnection->addQuotes( $value ),
			),
			array(
				'p_id' => $propertyId
			),
			__METHOD__
		);
	}

	/**
	 * @see PropertyStatisticsStore::addToUsageCounts
	 *
	 * @since 1.9
	 *
	 * @param array $additions
	 *
	 * @return boolean Success indicator
	 */
	public function addToUsageCounts( array $additions ) {
		$success = true;

		// TODO: properly implement this
		foreach ( $additions as $propertyId => $addition ) {
			$success = $this->addToUsageCount( $propertyId, $addition ) && $success;
		}

		return $success;
	}

	/**
	 * @see PropertyStatisticsStore::setUsageCount
	 *
	 * @since 1.9
	 *
	 * @param integer $propertyId
	 * @param integer $value
	 *
	 * @return boolean Success indicator
	 * @throws MWException
	 */
	public function setUsageCount( $propertyId, $value ) {
		if ( !is_int( $value ) || $value < 0 ) {
			throw new MWException( 'The value to add must be a positive integer' );
		}

		if ( !is_int( $propertyId ) || $propertyId <= 0 ) {
			throw new MWException( 'The property id to add must be a positive integer' );
		}

		return $this->dbConnection->update(
			$this->table,
			array(
				'usage_count' => $value,
			),
			array(
				'p_id' => $propertyId
			),
			__METHOD__
		);
	}

	/**
	 * @see PropertyStatisticsStore::insertUsageCount
	 *
	 * @since 1.9
	 *
	 * @param integer $propertyId
	 * @param integer $value
	 *
	 * @return boolean Success indicator
	 * @throws MWException
	 */
	public function insertUsageCount( $propertyId, $value ) {
		if ( !is_int( $value ) || $value < 0 ) {
			throw new MWException( 'The value to add must be a positive integer' );
		}

		if ( !is_int( $propertyId ) || $propertyId <= 0 ) {
			throw new MWException( 'The property id to add must be a positive integer' );
		}

		return $this->dbConnection->insert(
			$this->table,
			array(
				'usage_count' => $value,
				'p_id' => $propertyId,
			),
			__METHOD__
		);
	}

	/**
	 * @see PropertyStatisticsStore::getUsageCounts
	 *
	 * @since 1.9
	 *
	 * @param array $propertyIds
	 *
	 * @return array
	 */
	public function getUsageCounts( array $propertyIds ) {
		if ( $propertyIds === array() ) {
			return array();
		}

		$propertyStatistics = $this->dbConnection->select(
			$this->table,
			array(
				'usage_count',
				'p_id',
			),
			array(
				'p_id' => $propertyIds,
			),
			__METHOD__
		);

		$usageCounts = array();

		foreach ( $propertyStatistics as $propertyStatistic ) {
			assert( ctype_digit( $propertyStatistic->p_id ) );
			assert( ctype_digit( $propertyStatistic->usage_count ) );

			$usageCounts[(int)$propertyStatistic->p_id] = (int)$propertyStatistic->usage_count;
		}

		return $usageCounts;
	}

	/**
	 * @see PropertyStatisticsStore::deleteAll
	 *
	 * @since 1.9
	 *
	 * @return boolean Success indicator
	 */
	public function deleteAll() {
		return $this->dbConnection->delete(
			$this->table,
			'*',
			__METHOD__
		);
	}

}
