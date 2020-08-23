<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryController
{
    private $historyManager;
    private $responseFactory;

    public function __construct(CommandHistoryManagerInterface $historyManager)
    {
        $this->historyManager = $historyManager;
    }

    public function index()
    {
        $result = $this->historyManager->findAll();
        return new Response($result);
    }

    public function show($id)
    {
        
    }

    public function remove($id)
    {
        // todo: modify codes to remove history
        dd('create remove history logic here');
    }
}
