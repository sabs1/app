<?php

namespace Flags\Models;

class FlagType extends FlagsBaseModel {
	private
		$paramsVerified = false;

	public static $flagGroups = [
		1 => 'spoiler',
		2 => 'disambig',
		3 => 'canon',
		4 => 'stub',
		5 => 'delete',
		6 => 'improvements',
		7 => 'status',
		8 => 'other'
	];

	public static $flagTargeting = [
		1 => 'readers',
		2 => 'contributors'
	];

	public function getFlagGroupsMapping() {
		return self::$flagGroups;
	}

	public function getFlagGroupName( $flagGroupId ) {
		return self::$flagGroups[$flagGroupId];
	}

	public function getFlagGroupId ( $flagGroupName ) {
		return array_search( strtolower( $flagGroupName ), self::$flagGroups );
	}

	public function getFlagTargetingMapping() {
		return self::$flagTargeting;
	}

	public function getFlagTargetingName( $flagTargetingId ) {
		return self::$flagTargeting[$flagTargetingId];
	}

	public function getFlagTargetingId ( $flagTargetingName ) {
		return array_search( strtolower( $flagTargetingName ), self::$flagTargeting );
	}

	public function getFlagTypesForWikia( $wikiId ) {

	}

	public function verifyParamsForAdd( $params ) {
		$required = [ 'wikiId', 'flagGroup', 'flagName', 'flagView', 'flagTargeting' ];

		foreach ( $required as $requiredField ) {
			if ( !isset( $params[ $requiredField ] ) ) {
				return false; // Lack of a required parameter
			}
		}

		if ( !isset( self::$flagGroups[ $params['flagGroup'] ] ) ) {
			return false; // Unrecognized flag group
		}

		$this->paramsVerified = true;
		return true;
	}

	public function verifyParamsForRemove( $params ) {
		if ( !isset( $params['flagTypeId'] ) ) {
			return false;
		}

		$this->paramsVerified = true;
		return true;
	}

	/**
	 * @param $params
	 * @return bool
	 */
	public function addFlagType( $params ) {
		if ( !$this->paramsVerified ) {
			return false;
		}

		$db = $this->getDatabaseForWrite();

		$sql = ( new \WikiaSQL() )
			->INSERT( self::FLAGS_TYPES_TABLE )
			->SET( 'wiki_id', $params['wikiId'] )
			// flag_type_id is auto_increment
			->SET( 'flag_group', $params['flagGroup'] )
			->SET( 'flag_name', $params['flagName'] )
			->SET( 'flag_view', $params['flagView'] )
			->SET( 'flag_targeting', $params['flagTargeting'] );

		if ( $params['flagParamsNames'] !== null  ) {
			$sql->SET('flag_params_names', $params['flagParamsNames'] );
		}

		$sql->run( $db );

		$flagTypeId = $db->insertId();

		$db->commit();

		$this->paramsVerified = false;

		return $flagTypeId;
	}

	public function removeFlagType( $params ) {
		if ( !$this->paramsVerified ) {
			return false;
		}

		$db = $this->getDatabaseForWrite();

		$sql = ( new \WikiaSQL() )
			->DELETE( self::FLAGS_TYPES_TABLE )
			->WHERE( 'flag_type_id' )->EQUAL_TO( $params['flagTypeId'] )
			->run( $db );

		$status = $db->affectedRows() > 0;

		$db->commit();

		return $status;
	}
}
