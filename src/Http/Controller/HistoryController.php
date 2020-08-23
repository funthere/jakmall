<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
        return new JsonResponse($result);
    }

    public function show($id)
    {
        $result = $this->historyManager->findOne($id);
        return new JsonResponse($result);
    }

    public function remove($id)
    {
        $result = $this->historyManager->deleteOne($id);
        return new JsonResponse($result, 204);
    }
}
