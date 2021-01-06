--[[
    redis司机池 geo
    地理位置命令,司机灯登陆/出车,司机当前经纬度geo保存,georadius可以根据给定地理位置坐标获取指定范围内的地理位置集合
    key 经度 纬度 ID
--]]

redis.call('select',5)

local ret = redis.call('geoadd',KEYS[1],KEYS[2],KEYS[3],KEYS[4])
    return ret