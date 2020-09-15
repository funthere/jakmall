<?php

namespace Jakmall\Recruitment\Calculator\History\Libs;

class FileDatabase
{
    protected $file;

    public function __construct()
    {
        $this->file = dirname(__DIR__).'\..\..\db.json';

        if(!file_exists($this->file)) {
            $myfile = fopen($this->file, "w") or die("Unable to open file!");
            fclose($myfile);
        }
    }
    
    public function readAll()
    {
        $tempArray = [];
        $collection = collect([]);
        $result = file_get_contents($this->file);
        if(!$result || $result == 'null') {
            $result = [];
        } else {
            $tempArray = json_decode($result);
        }

        $decoded = $result != null ? json_decode($result) : null;
        if(is_array($decoded)) {
            foreach ($decoded as $key => $val) {
                $newVal['id'] = $val->id;
                $newVal['command'] = $val->command;
                $newVal['description'] = $val->description;
                $newVal['result'] = $val->result;
                $newVal['output'] = $val->output;
                $newVal['time'] = $val->time;

                $collection->push($newVal);
            }
        }

        return $collection;
    }

    public function store($data, $id)
    {
        $tempArray = [];
        $result = file_get_contents($this->file);
        if(!$result || $result == 'null') {
            $result = [];
        } else {
            $tempArray = json_decode($result);
        }

        $data['id'] = $id;
        array_push($tempArray, $data);
        $jsonData = json_encode($tempArray);

        return file_put_contents($this->file, $jsonData);
    }

    public function clear():bool
    {
        return file_put_contents($this->file, null);
    }

    public function whereIn($command)
    {
        $result = $this->readAll();

        if(count($result) > 0) {
            if(empty($command))
                return ($result->all());
            else
                return ($result->whereIn('command', $command)->all());
        }
        return [];
    }

    public function delete($id)
    {
        $result = $this->readAll();
        $jsonData = null;

        if(count($result) > 0) {
            $newData = [];
            foreach ($result as $key => $value) {
                if($value['id'] != $id) {
                    array_push($newData, $value);
                }
            }
        }
        $jsonData = json_encode($newData);

        return file_put_contents($this->file, $jsonData);
    }

}
