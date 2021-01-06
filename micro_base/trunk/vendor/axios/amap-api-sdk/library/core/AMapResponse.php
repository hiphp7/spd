<?php
/**
 * @author: axios
 *
 * @email: axiosleo@foxmail.com
 * @blog:  http://hanxv.cn
 * @datetime: 2018/3/27 16:05
 */

namespace amap\sdk\core;


class AMapResponse
{
    public $header;

    public $body;

    public $status;

    public function setHeader($header)
    {
        $this->header = $header;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getContent($format = "json")
    {
        if ($format === "json") {
            if (is_string($this->body)) {
                $result = json_decode($this->body, true);
                return empty($result) ? $this->body : $result;
            }
        }
        return $this->body;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getStatus()
    {
        return $this->status;
    }
}