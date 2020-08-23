<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use \Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryClearCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'history:clear';

    /**
     * @var string
     */
    protected $description = 'Clear saved history';

    public function __construct(CommandHistoryManagerInterface $historyManager)
    {
        parent::__construct();

        $this->historyManager = $historyManager;
    }

    protected function getCommandVerb(): string
    {
        return 'history:clear';
    }

    public function handle(): void
    {
        $clear = $this->historyManager->clearAll();
        $this->info('History cleared');

    }
}
