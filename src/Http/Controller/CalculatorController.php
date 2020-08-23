<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Illuminate\Http\Request;
use \Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class CalculatorController
{
    private $historyManager;

    public function __construct(CommandHistoryManagerInterface $historyManager)
    {

        $this->historyManager = $historyManager;
    }

    public function calculate(Request $request, $command)
    {
    	$numbers = $request->input('input');
    	$response = [];

		$description = $this->generateCalculationDescription($numbers, $command);
    	$result = $this->calculateAll($numbers, $command);
    	$output = sprintf('%s = %s', $description, $result);

		$response['command'] = $command;
		$response['description'] = $description;
		$response['result'] = $result;

    	// Log insert
        $toInsert = ['command' => $command, 'description' => $description, 'result' => $result, 'output' => $output, 'time' => date('Y-m-d H:i:s')];
        $this->historyManager->log($toInsert);

    	return json_encode($response);
    }

    protected function generateCalculationDescription(array $numbers, $command): string
    {
        $operator = $this->getOperator($command);
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    protected function getOperator($command): string
    {
    	switch ($command) {
    		case 'add':
    			return '+';
    			break;
    		case 'substract':
    			return '-';
    			break;
    		case 'multiply':
    			return '*';
    			break;
    		case 'divide':
    			return '/';
    			break;
    		case 'pow':
    			return '^';
    			break;
    		
    		default:
    			return '';
    			break;
    	}
    }

    /**
     * @param array $numbers
     *
     * @return float|int
     */
    protected function calculateAll(array $numbers, $command)
    {
        $number = array_pop($numbers);

        if (count($numbers) <= 0) {
            return $number;
        }

        return $this->calculateByOperator($this->calculateAll($numbers, $command), $number, $command);
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    protected function calculateByOperator($number1, $number2, $command)
    {
    	switch ($command) {
    		case 'add':
        		return $number1 + $number2;
    			break;
    		case 'substract':
        		return $number1 - $number2;
    			break;
    		case 'multiply':
        		return $number1 * $number2;
    			break;
    		case 'divide':
        		return $number1 / $number2;
    			break;
    		case 'pow':
        		return pow($number1, $number2);
    			break;
    		
    		default:
        		return 0;
    			break;
    	}
    }
}
