<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: message.proto

namespace Micro\Common\Protobuf\Service;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;
use Micro\Common\Protobuf\Service\GPBMetadata\Message;

/**
 * Generated from protobuf message <code>micro.common.protobuf.service.Request</code>
 */
class Request extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string id = 1;</code>
     */
    private $id = '';
    /**
     * Generated from protobuf field <code>string param = 2;</code>
     */
    private $param = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $id
     *     @type string $param
     * }
     */
    public function __construct($data = NULL) {
        Message::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string id = 1;</code>
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Generated from protobuf field <code>string id = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkString($var, True);
        $this->id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string param = 2;</code>
     * @return string
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Generated from protobuf field <code>string param = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setParam($var)
    {
        GPBUtil::checkString($var, True);
        $this->param = $var;

        return $this;
    }

}
