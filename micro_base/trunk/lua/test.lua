
redis.call('select',5)

local ret = redis.call('hgetall',KEYS[1])
    return ret