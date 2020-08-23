<?php

namespace Jakmall\Recruitment\Calculator\History\Service;

use Illuminate\Database\Capsule\Manager as DB;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class CommandHistoryManagerService implements CommandHistoryManagerInterface
{

    public function __construct()
    {

    }
    /**
     * Returns array of command history.
     *
     * @return array
     */
    public function findAll(): array
    {
        $result = DB::table('log')->get();
        $data = $this->getArray($result);

        return $data->all();
    }

    /**
     * Log command data to storage.
     *
     * @param mixed $command The command to log.
     *
     * @return bool Returns true when command is logged successfully, false otherwise.
     */
    public function log($command): bool
    {
        $insertDb = DB::connection('mysql')->table('log')->insert($command);
        $insertFile = DB::connection('sqlite')->table('log')->insert($command);

        return $insertDb && $insertFile;
    }

    /**
     * Clear all data from storage.
     *
     * @return bool Returns true if all data is cleared successfully, false otherwise.
     */
    public function clearAll():bool
    {
        $clearDb = DB::connection('mysql')->table('log')->delete();
        $clearFile = DB::connection('sqlite')->table('log')->delete();

        return $clearDb && $clearFile;
    }

    public function find($command, $driver = 'database'): array
    {
        $connection = 'default';

        if($driver == 'database') {
            $connection = 'mysql';
        } else {
            $connection = 'sqlite';
        }

        $result = DB::connection($connection)->table('log');
        if(count($command) > 0) {
            $result = $result->whereIn('command', $command);
        }

        $result = $result->get();
        $data = $this->getArray($result);

        return $data->all();
    }

    public function findOne($id)
    {
        $item = DB::table('log')->where('id', $id)->first();
        return [
            'id' => $item->id,
            'command' => ucwords($item->command),
            'description' => $item->description,
            'result' => $item->result,
            'output' => $item->output,
            'time' => $item->time,
        ];
    }

    public function deleteOne($id)
    {
        $deleteDb = DB::connection('mysql')->table('log')->where('id', $id)->delete();
        $deleteFile = DB::connection('sqlite')->table('log')->where('id', $id)->delete();

        return $deleteDb && $deleteFile;
    }

    private function getArray($result)
    {
        return $result->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'command' => ucwords($item->command),
                'description' => $item->description,
                'result' => $item->result,
                'output' => $item->output,
                'time' => $item->time,
            ];
        });
    }
}
