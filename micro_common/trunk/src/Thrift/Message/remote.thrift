//thrift -r --out ./ --gen php:server Structs/remote.thrift

namespace php Micro.Common.Thrift.Service  # 指定生成什么语言，生成文件存放的目录
//namespace java cn.nxp.thrift

// 返回结构体
struct Response {
    1: string code;    // 返回状态码
    2: string msg;  // 码字回提示语名
    3: string data; // 返回内容
}

// 服务体
service RemoteService {
    // json 字符串参数  客户端请求方法
    Response handle(1:string params)
}