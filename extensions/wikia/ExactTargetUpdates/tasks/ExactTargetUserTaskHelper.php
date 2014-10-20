<?php

class ExactTargetUserTaskHelper {

	public function prepareSubscriberCreateParams() {

	}

	public function prepareSubscriberRetrieveParams() {

	}

	public function prepareSubscriberUpdateParams() {

	}

	public function prepareSubscriberDeleteParams() {

	}

	public function prepareUserCreateParams() {

	}

	public function prepareUserRetrieveParams() {

	}

	/**
	 * Prepares array of params for ExactTarget API for creating DataExtension objects for user table
	 * Assumes $aUserData has user_id key that will be treated as filter to update data
	 * @param array $aUserData user key value array
	 * @return array
	 */
	public function prepareUserUpdateParams( array $aUserData ) {
		$userId = $this->extractUserIdFromData( $aUserData );
		/* Get Customer Keys specific for production or development */
		$aCustomerKeys = ExactTargetUpdatesHelper::getCustomerKeys();
		$sCustomerKey = $aCustomerKeys[ 'user' ];

		$aApiParams = [
			[
				'DataExtension' => [
					'CustomerKey' => $sCustomerKey,
					'Properties' => $aUserData,
					'Keys' => ['user_id' => $userId ]
				]
			]
		];
		return $aApiParams;
	}

	public function prepareUserDeleteParams( $userId ) {
		/* Get Customer Keys specific for production or development */
		$aCustomerKeys = ExactTargetUpdatesHelper::getCustomerKeys();
		$sCustomerKey = $aCustomerKeys[ 'user' ];

		$aApiParams = [
			[
				'DataExtension' => [
					'CustomerKey' => $sCustomerKey,
					'Keys' => ['user_id' => $userId ]
				]
			]
		];
		return $aApiParams;
	}

	/**
	 * Prepares DataExtension params for prepareApiCreateParams method
	 * @param int $iUserId
	 * @param array $aUserProperties array of property name => value pairs
	 * @return array
	 */
	public function prepareUserPropertiesCreateParams( $iUserId, array $aUserProperties ) {
		$aDataExtensionsParams = [];
		foreach ( $aUserProperties as $sProperty => $sValue ) {
			$aDataExtensionsParams[] = [
				'up_user' => $iUserId,
				'up_property' => $sProperty,
				'up_value' => $sValue
			];
		}
		return $aDataExtensionsParams;
	}

	public function prepareUserPropertiesRetrieveParams() {

	}

	/**
	 * Prepares array of params for ExactTarget API for creating DataExtension objects for user table
	 * @param int $iUserId User id
	 * @param array $aUserProperties user_properties key value array
	 * @return array
	 */
	public function prepareUserPropertiesUpdateParams( $iUserId, array $aUserProperties ) {
		/* Get Customer Keys specific for production or development */
		$aCustomerKeys = ExactTargetUpdatesHelper::getCustomerKeys();
		$sCustomerKey = $aCustomerKeys['user_properties'];

		foreach ( $aUserProperties as $sProperty => $sValue ) {
			$aApiParams = [
				[
					'DataExtension' => [
						'CustomerKey' => $sCustomerKey,
						'Properties' => [ 'up_value' => $sValue ],
						'Keys' => [
							'up_user' => $iUserId,
							'up_property' => $sProperty
						]
					]
				]
			];
		}
		return $aApiParams;
	}

	/**
	 * Prepares array of params for ExactTarget API for removing DataExtension objects for user_properties table
	 * @param int $iUserId id of user to be deleted
	 * @return array
	 */
	public function prepareUserPropertiesDeleteParams( $iUserId ) {
		/*
		 * @var array $aUserPropertiesNames list of user properties sent to ExactTarget
		 * (see ExactTargetUpdatesHooks::prepareUserPropertiesParams)
		 */
		$aUserProperties = [
			'marketingallowed',
			'unsubscribed',
			'language'
		];
		/* Get Customer Keys specific for production or development */
		$aCustomerKeys = ExactTargetUpdatesHelper::getCustomerKeys();
		$sCustomerKey = $aCustomerKeys['user_properties'];

		foreach ( $aUserProperties as $sProperty => $sValue ) {
			$aApiParams = [
				[
					'DataExtension' => [
						'CustomerKey' => $sCustomerKey,
						'Keys' => [
							'up_user' => $iUserId,
							'up_property' => $sProperty
						]
					]
				]
			];
		}
		return $aApiParams;
	}

	/**
	 * Returns array of params for ExactTarget API for creating DataExtension objects
	 * @param array $aDataExtensionsParams
	 * @param string $sCustomerKey
	 * @return array
	 * e.g result params for creating two DataExtenision objects
	 * [
	 *   [
	 *     'DataExtension' => [
	 *       'CustomerKey' => 'user_properties',
	 *       'Properties' => ['key'='value']
	 *     ]
	 *   ]
	 *   [
	 *     'DataExtension' => [
	 *       'CustomerKey' => 'user_properties',
	 *       'Properties' => ['key'='value']
	 *     ]
	 *   ]
	 * ]
	 */
	public function prepareApiCreateParams( $aDataExtensionsParams, $sCustomerKey ) {
		$aApiParams = [];
		foreach ( $aDataExtensionsParams as $aProperties ) {
			$aApiParams[] = [
				'DataExtension' => [
					'CustomerKey' => $sCustomerKey,
					'Properties' => $aProperties
				]
			];
		}
		return $aApiParams;
	}

	/**
	 * Returns user_id element from $aUserData array and removes it from array
	 * @param array $aUserData key value data from user table
	 * @return int
	 */
	public function extractUserIdFromData( &$aUserData ) {
		$iUserId = $aUserData[ 'user_id' ];
		unset( $aUserData[ 'user_id' ] );
		return $iUserId;
	}
}
