<?php
/**
 *  Copyright 2015 SmartBear Software
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

/**
 *
 * NOTE: This class is auto generated by the swagger code generator program. Do not edit the class manually.
 */

namespace Swagger\Client\User\Preferences\Api;

use \Swagger\Client\Configuration;
use \Swagger\Client\ApiClient;
use \Swagger\Client\ApiException;
use \Swagger\Client\ObjectSerializer;

class UserPreferencesApi {

  /** @var \Swagger\Client\ApiClient instance of the ApiClient */
  private $apiClient;

  /**
   * @param \Swagger\Client\ApiClient|null $apiClient The api client to use
   */
  function __construct($apiClient = null) {
    if ($apiClient == null) {
      $apiClient = new ApiClient();
      $apiClient->getConfig()->setHost('https://localhost/user-preference');
    }

    $this->apiClient = $apiClient;
  }

  /**
   * @return \Swagger\Client\ApiClient get the API client
   */
  public function getApiClient() {
    return $this->apiClient;
  }

  /**
   * @param \Swagger\Client\ApiClient $apiClient set the API client
   * @return UserPreferencesApi
   */
  public function setApiClient(ApiClient $apiClient) {
    $this->apiClient = $apiClient;
    return $this;
  }

  
  /**
   * getUserPreferences
   *
   * Returns all the global user preferences for a user
   *
   * @param int $user_id The id of the user to list the preferences (required)
   * @return \Swagger\Client\User\Preferences\Models\Preference[]
   * @throws \Swagger\Client\ApiException on non-2xx response
   */
   public function getUserPreferences($user_id) {
      
      // verify the required parameter 'user_id' is set
      if ($user_id === null) {
        throw new \InvalidArgumentException('Missing the required parameter $user_id when calling getUserPreferences');
      }
      

      // parse inputs
      $resourcePath = "/{userId}";
      $resourcePath = str_replace("{format}", "json", $resourcePath);
      $method = "GET";
      $httpBody = '';
      $queryParams = array();
      $headerParams = array();
      $formParams = array();
      $_header_accept = ApiClient::selectHeaderAccept(array('application/json'));
      if (!is_null($_header_accept)) {
        $headerParams['Accept'] = $_header_accept;
      }
      $headerParams['Content-Type'] = ApiClient::selectHeaderContentType(array());

      
      
      // path params
      if($user_id !== null) {
        $resourcePath = str_replace("{" . "userId" . "}",
                                    $this->apiClient->getSerializer()->toPathValue($user_id),
                                    $resourcePath);
      }
      
      

      // for model (json/xml)
      if (isset($_tempBody)) {
        $httpBody = $_tempBody; // $_tempBody is the method argument, if present
      } else if (count($formParams) > 0) {
        // for HTTP post (form)
        $httpBody = $formParams;
      }
      
      $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-AccessToken');
      if (isset($apiKey)) {
        $headerParams['X-Wikia-AccessToken'] = $apiKey;
      }
      
      
      
      $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-UserId');
      if (isset($apiKey)) {
        $headerParams['X-Wikia-UserId'] = $apiKey;
      }
      
      
      
      // make the API Call
      try {
        $response = $this->apiClient->callAPI($resourcePath, $method,
                                              $queryParams, $httpBody,
                                              $headerParams);
      } catch (ApiException $e) {
        switch ($e->getCode()) { 
          case 200:
            $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Swagger\Client\User\Preferences\Models\Preference[]');
            $e->setResponseObject($data);
            break;
          case 404:
            $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Swagger\Client\User\Preferences\Models\Problem');
            $e->setResponseObject($data);
            break;
        }

        throw $e;
      }
      
      if (!$response) {
        return null;
      }

      $responseObject = $this->apiClient->getSerializer()->deserialize($response,'\Swagger\Client\User\Preferences\Models\Preference[]');
      return $responseObject;
      
  }
  
  /**
   * updateUserPreferences
   *
   * Update more than one user preference
   *
   * @param int $user_id The id of the user to list the preferences (required)
   * @param \Swagger\Client\User\Preferences\Models\Preference[] $updated_user_preferences An array of user preference objects (required)
   * @return void
   * @throws \Swagger\Client\ApiException on non-2xx response
   */
   public function updateUserPreferences($user_id, $updated_user_preferences) {
      
      // verify the required parameter 'user_id' is set
      if ($user_id === null) {
        throw new \InvalidArgumentException('Missing the required parameter $user_id when calling updateUserPreferences');
      }
      

      // parse inputs
      $resourcePath = "/{userId}";
      $resourcePath = str_replace("{format}", "json", $resourcePath);
      $method = "PUT";
      $httpBody = '';
      $queryParams = array();
      $headerParams = array();
      $formParams = array();
      $_header_accept = ApiClient::selectHeaderAccept(array('application/json'));
      if (!is_null($_header_accept)) {
        $headerParams['Accept'] = $_header_accept;
      }
      $headerParams['Content-Type'] = ApiClient::selectHeaderContentType(array());

      
      
      // path params
      if($user_id !== null) {
        $resourcePath = str_replace("{" . "userId" . "}",
                                    $this->apiClient->getSerializer()->toPathValue($user_id),
                                    $resourcePath);
      }
      
      // body params
      $_tempBody = null;
      if (isset($updated_user_preferences)) {
        $_tempBody = $updated_user_preferences;
      }

      // for model (json/xml)
      if (isset($_tempBody)) {
        $httpBody = $_tempBody; // $_tempBody is the method argument, if present
      } else if (count($formParams) > 0) {
        // for HTTP post (form)
        $httpBody = $formParams;
      }
      
      $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-AccessToken');
      if (isset($apiKey)) {
        $headerParams['X-Wikia-AccessToken'] = $apiKey;
      }
      
      
      
      $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-UserId');
      if (isset($apiKey)) {
        $headerParams['X-Wikia-UserId'] = $apiKey;
      }
      
      
      
      // make the API Call
      try {
        $response = $this->apiClient->callAPI($resourcePath, $method,
                                              $queryParams, $httpBody,
                                              $headerParams);
      } catch (ApiException $e) {
        switch ($e->getCode()) { 
          case 400:
            $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Swagger\Client\User\Preferences\Models\Problem');
            $e->setResponseObject($data);
            break;
        }

        throw $e;
      }
      
  }
  
  /**
   * updateUserPreference
   *
   * Update one user preference
   *
   * @param int $user_id The id of the user whose preference is to be updated/created (required)
   * @param string $preference_name The preference name to update/create (required)
   * @param string $updated_value the preference value (required)
   * @return void
   * @throws \Swagger\Client\ApiException on non-2xx response
   */
   public function updateUserPreference($user_id, $preference_name, $updated_value) {
      
      // verify the required parameter 'user_id' is set
      if ($user_id === null) {
        throw new \InvalidArgumentException('Missing the required parameter $user_id when calling updateUserPreference');
      }
      
      // verify the required parameter 'preference_name' is set
      if ($preference_name === null) {
        throw new \InvalidArgumentException('Missing the required parameter $preference_name when calling updateUserPreference');
      }
      

      // parse inputs
      $resourcePath = "/{userId}/{preferenceName}";
      $resourcePath = str_replace("{format}", "json", $resourcePath);
      $method = "PUT";
      $httpBody = '';
      $queryParams = array();
      $headerParams = array();
      $formParams = array();
      $_header_accept = ApiClient::selectHeaderAccept(array('application/json'));
      if (!is_null($_header_accept)) {
        $headerParams['Accept'] = $_header_accept;
      }
      $headerParams['Content-Type'] = ApiClient::selectHeaderContentType(array());

      
      
      // path params
      if($user_id !== null) {
        $resourcePath = str_replace("{" . "userId" . "}",
                                    $this->apiClient->getSerializer()->toPathValue($user_id),
                                    $resourcePath);
      }// path params
      if($preference_name !== null) {
        $resourcePath = str_replace("{" . "preferenceName" . "}",
                                    $this->apiClient->getSerializer()->toPathValue($preference_name),
                                    $resourcePath);
      }
      
      // body params
      $_tempBody = null;
      if (isset($updated_value)) {
        $_tempBody = $updated_value;
      }

      // for model (json/xml)
      if (isset($_tempBody)) {
        $httpBody = $_tempBody; // $_tempBody is the method argument, if present
      } else if (count($formParams) > 0) {
        // for HTTP post (form)
        $httpBody = $formParams;
      }
      
      $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-AccessToken');
      if (isset($apiKey)) {
        $headerParams['X-Wikia-AccessToken'] = $apiKey;
      }
      
      
      
      $apiKey = $this->apiClient->getApiKeyWithPrefix('X-Wikia-UserId');
      if (isset($apiKey)) {
        $headerParams['X-Wikia-UserId'] = $apiKey;
      }
      
      
      
      // make the API Call
      try {
        $response = $this->apiClient->callAPI($resourcePath, $method,
                                              $queryParams, $httpBody,
                                              $headerParams);
      } catch (ApiException $e) {
        switch ($e->getCode()) { 
          case 400:
            $data = $this->apiClient->getSerializer()->deserialize($e->getResponseBody(), '\Swagger\Client\User\Preferences\Models\Problem');
            $e->setResponseObject($data);
            break;
        }

        throw $e;
      }
      
  }
  
}
