<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/17
 * Time: 14:19
 */

namespace Micro\Common\Thrift\Client;


use Micro\Common\Thrift\Service\RemoteServiceIf;
use Micro\Common\Thrift\Service\RemoteService_handle_args;
use Micro\Common\Thrift\Service\RemoteService_handle_result;
use Thrift\Type\TMessageType;
use Thrift\Protocol\TBinaryProtocolAccelerated;
use Thrift\Exception\TApplicationException;

class ClientHandler implements RemoteServiceIf
{
    protected $input_ = null;
    protected $output_ = null;
    protected $seqid_ = 0;

    public function __construct($input, $output=null) {
        $this->input_ = $input;
        $this->output_ = $output ? $output : $input;
    }

    public function handle($params)
    {
        $this->send_handle($params);
        return $this->recv_handle();
    }

    public function send_handle($params)
    {
        $args = new RemoteService_handle_args();
        $args->params = $params;
        $bin_accel = ($this->output_ instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_write_binary');
        if ($bin_accel) {

            thrift_protocol_write_binary(
                $this->output_,
                'handle',
                TMessageType::CALL,
                $args,
                $this->seqid_,
                $this->output_->isStrictWrite()
            );
        } else {
            $this->output_->writeMessageBegin('handle', TMessageType::CALL, $this->seqid_);
            $args->write($this->output_);
            $this->output_->writeMessageEnd();
            $this->output_->getTransport()->flush();
        }
    }

    public function recv_handle()
    {
        $bin_accel = ($this->input_ instanceof TBinaryProtocolAccelerated) && function_exists('thrift_protocol_read_binary');
        if ($bin_accel) {
            $result = thrift_protocol_read_binary(
                $this->input_,
                '\Micro\Common\Thrift\Service\RemoteService_handle_result',
                $this->input_->isStrictRead()
            );
        } else {
            $rseqid = 0;
            $fname = null;
            $mtype = 0;

            $this->input_->readMessageBegin($fname, $mtype, $rseqid);
            if ($mtype == TMessageType::EXCEPTION) {
                $x = new TApplicationException();
                $x->read($this->input_);
                $this->input_->readMessageEnd();
                throw $x;
            }
            $result = new RemoteService_handle_result();
            $result->read($this->input_);
            $this->input_->readMessageEnd();
        }
        if ($result->success !== null) {
            return $result->success;
        }
        throw new \Exception("handle failed: unknown result");
    }

}