<?php

namespace app\command;

use app\common\repository\LevelRepository;
use think\facade\Cache;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use app\admin\model\LevelModel;

class RefreshLevelPassword extends Command
{
    protected function configure()
    {
        $this->setName('refresh_level_password')
            ->setDescription('Refresh expired advertisement codes and update expiration times');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('Start refreshing expired advertisement codes...');

        // Get current timestamp
        $currentTime = time();
        // Get distinct types that need password refresh
        $typeRecords = Db::name('level')
            ->where('password_expire_time', '<', $currentTime)
            ->where('password_expire_time', '>', 0)
            ->where('password_refresh_frequency', '>', 0)
            ->group('type')
            ->select();
        if (empty($typeRecords)) {
            $output->writeln('No expired advertisement codes found.');
            return;
        }

        $totalCount = 0;

        foreach ($typeRecords as $typeRecord) {
            $type = $typeRecord['type'];
            // Generate one random password per type
            $newPassword = $this->generateRandomPassword();

            // Get a common refresh frequency for this type (using minimum as a safe default)
            $minutes = $typeRecord['password_refresh_frequency'];
            $newExpireTime = $currentTime + ($minutes * 60);
            // Update all levels of this type at once
            $affectedRows = Db::name('level')
                ->where('type', $type)
                ->update([
                    'invest_password'      => $newPassword,
                    'password_expire_time' => $newExpireTime
                ]);

            $this->clearCache('level_password', $type);
            $totalCount += $affectedRows;

            $typeName = LevelRepository::$typeMap[$type] ?? 'Type '.$type;

            $output->writeln("Updated {$affectedRows} level(s) for {$typeName}: New code: {$newPassword}, Expires: ".date('Y-m-d H:i:s',
                    $newExpireTime));
        }

        $output->writeln("Refreshed {$totalCount} advertisement codes successfully.");
    }

    /**
     * Generate a random 6-digit password
     * @return string
     */
    private function generateRandomPassword(): string
    {
        $password = '';
        for ($i = 0; $i < 6; $i++) {
            $password .= mt_rand(0, 9);
        }
        return $password;
    }

    public function clearCache($key, $value)
    {
        $redis = Cache::store('redis');
        $redis_key = config("redis_cache_key.".$key).$value;
        $redis->delete($redis_key);
    }
}
