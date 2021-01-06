<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/17
 * Time: 09:18
 */

namespace Micro\Common\Thrift\Server;


use Thrift\Exception\TTransportException;
use Thrift\Factory\TStringFuncFactory;
use Thrift\Transport\TFramedTransport;

class SwooleTFramedTransport extends TFramedTransport
{
    public $buffer = '';
    public $offset = 0;
    public $server;
    protected  $fd;
    protected $read_ = true;
    protected $rBuf_ = '';
    protected $wBuf_ = '';

    /**
     * @return mixed
     */
    public function setHandle($fd)
    {
         $this->fd = $fd;
    }

    function readFrame()
    {
        $buf = $this->_read(4);
        $val = unpack('N', $buf);
        $sz = $val[1];
        $this->rBuf_ = $this->_read($sz);
    }
    public function _read($len)
    {
        if (strlen($this->buffer) - $this->offset < $len)
        {
            throw new TTransportException('TSocket['.strlen($this->buffer).'] read '.$len.' bytes failed.');
        }
        $data = substr($this->buffer, $this->offset, $len);
        $this->offset += $len;
        return $data;
    }
    public function read($len) {
        if (!$this->read_) {
            return $this->_read($len);
        }
        if (TStringFuncFactory::create()->strlen($this->rBuf_) === 0) {
            $this->readFrame();
        }
        // Just return full buff
        if ($len >= TStringFuncFactory::create()->strlen($this->rBuf_)) {
            $out = $this->rBuf_;
            $this->rBuf_ = null;
            return $out;
        }
        // Return TStringFuncFactory::create()->substr
        $out = TStringFuncFactory::create()->substr($this->rBuf_, 0, $len);
        $this->rBuf_ = TStringFuncFactory::create()->substr($this->rBuf_, $len);
        return $out;
    }
    public function write($buf,$len=NULL)
    {
        $this->wBuf_ .= $buf;
    }
    function flush()
    {
        $out = pack('N', strlen($this->wBuf_));
        $out .= $this->wBuf_;
        $this->server->send($this->fd, $out);
        $this->wBuf_ = '';
    }
}