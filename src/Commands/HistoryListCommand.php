<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use \Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryListCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'history:list {commands?*} {--driver=}';

    /**
     * @var string
     */
    protected $description = 'Show calculator history';

    public function __construct(CommandHistoryManagerInterface $historyManager)
    {
        parent::__construct();

        $this->historyManager = $historyManager;
    }

    public function handle(): void
    {
        $commands = $this->argument('commands');
        $driver = 'database';
        $driver = $this->option('driver');
        if($driver && $driver == 'file') {
            $driver = 'file';
        }
        $result = $this->historyManager->find($commands, $driver);

        $header = ['No', 'Command', 'Description', 'Result', 'Output', 'Time'];

        if(count($result) > 0) {
            $this->table($header, $result);
        } else {
            $this->info('History is empty.');
        }

    }
}
