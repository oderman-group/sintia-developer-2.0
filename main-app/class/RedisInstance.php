<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");

class RedisInstance {
    private static $redis;

    /**
     * Establishes a connection to the Redis server.
     *
     * This method creates a new Redis instance, connects it to the specified Redis
     * server and port, and authenticates it with the provided password.
     *
     * @return void
     */
    private static function connect() {
        self::$redis = new Redis();
        self::$redis->connect(REDIS_SERVER, REDIS_PORT);
        self::$redis->auth(REDIS_PASSWORD);
    }

    /**
     * Retrieves the Redis instance.
     *
     * If the Redis instance is not already created, this method establishes a
     * connection by calling the connect method. It then returns the Redis instance
     * for use in the application.
     *
     * @return Redis The Redis instance.
     */
    public static function getRedisInstance() {
        if (!self::$redis) {
            self::connect();
        }

        return self::$redis;
    }
}