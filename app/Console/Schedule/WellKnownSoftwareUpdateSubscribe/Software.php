<?php

namespace App\Console\Schedule\WellKnownSoftwareUpdateSubscribe;

use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\Go;
use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\Kernel;
use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\KernelFeodra;
use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\MariaDB;
use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\MariaDBDocker;
use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\Nginx;
use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\NodeJS;
use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\OpenSSL;
use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\PHP;
use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\Redis;
use App\Console\Schedule\WellKnownSoftwareUpdateSubscribe\Softwares\RedisDocker;

enum Software: string
{
    case PHP = 'PHP';
    case Nginx = 'NGINX';
    case MariaDB = 'MARIADB';
    case MariaDBDocker = 'MARIADB_DOCKER';
    case Redis = 'REDIS';
    case RedisDocker = 'REDIS_DOCKER';
    case OpenSSL = 'OPENSSL';
    case Go = 'GO';
    case NodeJS = 'NODEJS';
    case Kernel = 'KERNEL';
    case KernelFeodra = 'KERNEL_FEDORA';

    public function file(): string
    {
        return match ($this) {
            self::PHP => __DIR__ . '/Softwares/PHP.php',
            self::Nginx => __DIR__ . '/Softwares/Nginx.php',
            self::MariaDB => __DIR__ . '/Softwares/MariaDB.php',
            self::MariaDBDocker => __DIR__ . '/Softwares/MariaDBDocker.php',
            self::Redis => __DIR__ . '/Softwares/Redis.php',
            self::RedisDocker => __DIR__ . '/Softwares/RedisDocker.php',
            self::OpenSSL => __DIR__ . '/Softwares/OpenSSL.php',
            self::Go => __DIR__ . '/Softwares/Go.php',
            self::NodeJS => __DIR__ . '/Softwares/NodeJS.php',
            self::Kernel => __DIR__ . '/Softwares/Kernel.php',
            self::KernelFeodra => __DIR__ . '/Softwares/KernelFeodra.php',
        };
    }

    public function getInstance(): SoftwareInterface
    {
        if (!isset(Common::$instances[$this->value]) || !Common::$instances[$this->value] instanceof SoftwareInterface) {
            Common::$instances[$this->value] = new ($this->getClass());
        }
        return Common::$instances[$this->value];
    }

    public function getClass(): string
    {
        return match ($this) {
            self::PHP => PHP::class,
            self::Nginx => Nginx::class,
            self::MariaDB => MariaDB::class,
            self::MariaDBDocker => MariaDBDocker::class,
            self::Redis => Redis::class,
            self::RedisDocker => RedisDocker::class,
            self::OpenSSL => OpenSSL::class,
            self::Go => Go::class,
            self::NodeJS => NodeJS::class,
            self::Kernel => Kernel::class,
            self::KernelFeodra => KernelFeodra::class,
        };
    }
}
