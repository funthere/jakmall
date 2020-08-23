<?php

namespace Jakmall\Recruitment\Calculator\Commands;

use Illuminate\Console\Command;
use \Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class DivideCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'divide {numbers*}';

    /**
     * @var string
     */
    protected $description = 'Divide all given Numbers';

    private $historyManager;

    public function __construct(CommandHistoryManagerInterface $historyManager)
    {
        parent::__construct();
        $commandVerb = $this->getCommandVerb();

        $this->signature = sprintf(
            '%s {numbers* : The numbers to be %s}',
            $commandVerb,
            $this->getCommandPassiveVerb()
        );
        $this->description = sprintf('%s all given Numbers', ucfirst($commandVerb));

        $this->historyManager = $historyManager;
    }

    protected function getCommandVerb(): string
    {
        return 'divide';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'divided';
    }

    public function handle(): void
    {
        $numbers = $this->getInput();
        $description = $this->generateCalculationDescription($numbers);
        $result = $this->calculateAll($numbers);

        $this->comment(sprintf('%s = %s', $description, $result));
        $toInsert = ['command' => $this->getCommandVerb(), 'description' => $description, 'result' => $result, 'output' => sprintf('%s = %s', $description, $result), 'time' => date('Y-m-d H:i:s')];
        $this->historyManager->log($toInsert);
    }

    protected function getInput(): array
    {
        return $this->argument('numbers');
    }

    protected function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    protected function getOperator(): string
    {
        return '/';
    }

    /**
     * @param array $numbers
     *
     * @return float|int
     */
    protected function calculateAll(array $numbers)
    {
        $number = array_pop($numbers);

        if (count($numbers) <= 0) {
            return $number;
        }

        return $this->calculate($this->calculateAll($numbers), $number);
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    protected function calculate($number1, $number2)
    {
        return $number1 / $number2;
    }
}
