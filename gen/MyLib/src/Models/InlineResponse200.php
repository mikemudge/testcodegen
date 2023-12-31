<?php
/**
 * InlineResponse200
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * Simple API Test
 *
 * Testing a generic API with different schemas
 *
 * OpenAPI spec version: 1.0.0
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 3.0.46
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\Models;

use \ArrayAccess;
use \Swagger\Client\ObjectSerializer;

/**
 * InlineResponse200 Class Doc Comment
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class InlineResponse200 implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'inline_response_200';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'purchaseId' => 'string',
        'purchaseKey' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'purchaseId' => null,
        'purchaseKey' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'purchaseId' => 'purchase_id',
        'purchaseKey' => 'purchase_key'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'purchaseId' => 'setPurchaseId',
        'purchaseKey' => 'setPurchaseKey'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'purchaseId' => 'getPurchaseId',
        'purchaseKey' => 'getPurchaseKey'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    const PURCHASE_KEY_SID = 'sid';
    const PURCHASE_KEY_EXTID = 'extid';

    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getPurchaseKeyAllowableValues()
    {
        return [
            self::PURCHASE_KEY_SID
            self::PURCHASE_KEY_EXTID
        ];
    }

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['purchaseId'] = isset($data['purchaseId']) ? $data['purchaseId'] : null;
        $this->container['purchaseKey'] = isset($data['purchaseKey']) ? $data['purchaseKey'] : 'sid';
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['purchaseId'] === null) {
            $invalidProperties[] = "'purchaseId' can't be null";
        }
        $allowedValues = $this->getPurchaseKeyAllowableValues();
        if (!is_null($this->container['purchaseKey']) && !in_array($this->container['purchaseKey'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'purchaseKey', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets purchaseId
     *
     * @return string
     */
    public function getPurchaseId()
    {
        return $this->container['purchaseId'];
    }

    /**
     * Sets purchaseId
     *
     * @param string $purchaseId purchaseId
     *
     * @return $this
     */
    public function setPurchaseId($purchaseId)
    {
        $this->container['purchaseId'] = $purchaseId;

        return $this;
    }

    /**
     * Gets purchaseKey
     *
     * @return string
     */
    public function getPurchaseKey()
    {
        return $this->container['purchaseKey'];
    }

    /**
     * Sets purchaseKey
     *
     * @param string $purchaseKey purchaseKey
     *
     * @return $this
     */
    public function setPurchaseKey($purchaseKey)
    {
        $allowedValues = $this->getPurchaseKeyAllowableValues();
        if (!is_null($purchaseKey) && !in_array($purchaseKey, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'purchaseKey', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['purchaseKey'] = $purchaseKey;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}
