<?php

/**
 * ToDo model for the application.
 * Handles access to JSON Data Base.
 */
class ToDoModel_json {

    ################################################
    # CRUD: CLASS METHODS TO OPERATE WITH DATABASE #    
    ################################################

    // CREATE: method that creates a task and adds it to the array of tasks
    public function createTask(string $name, string $author): bool | string {

        // get all tasks
        $tasks = $this->getTasks();
        
        // check if len of tasks > 0. else new taskId = 1
        if (count($tasks) >0) {
            // new taskId =  last taskId + 1
            $new_taskId = end($tasks)['id'] + 1;
        } else {
            $new_taskId = 1;
        }

        $new_task['id'] = $new_taskId;
        $new_task['name'] = $name;
        $new_task['start_time'] = null;
        $new_task['end_time'] = null;
        $new_task['author'] = $author;
        $new_task['status'] = 'Pending';

        // add the new task object to all tasks
        $tasks[] = $new_task;

        return $this->saveTasks($tasks);

    }
    
    // READ: method that returns an array containing all tasks
    public function getTasks(): array{
        (string) $jsonFile = file_get_contents(ROOT_PATH.'/app/models/data/json/data.json');
        (array) $tasks = json_decode($jsonFile, true);  // returns array of task objects
        return $tasks;
    }
    
    // READ: method that gets a task by its 'id' from 'db_type' DataBase 
    public function getTaskById($id){
        // missing error handling ****************************
        // get all tasks
        $tasks = $this->getTasks();

        foreach($tasks as $task) {
            if ($task['id'] == $id) {
              return $task;
            }
        }
        return null;
    }

    // UPDATE: method that updates a task and the array of tasks
    public function updateTask(array $data, int $id): bool {

        $tasks = $this->getTasks();

        //if status has changed to 'Ongoing', sets 'start_time': current date and time & 'end_time': NULL
        if ($data['status'] == 'Ongoing' && $tasks[$id]['status']  != 'Ongoing') {
            $tasks[$id]['start_time'] = date("Y-m-d H:i:s", time());
            $tasks[$id]['end_time'] = null;
        }
        // if status has changed to 'Finished' from 'Ongoing', sets 'end_time': current date and time
        elseif ( $data['status'] == 'Finished' && $tasks[$id]['status'] == 'Ongoing'){
            $tasks[$id]['end_time'] = date("Y-m-d H:i:s", time());
        }
        // if status has changed to 'Finished' from 'Pending', sets 'start/end_time': current date and time
        elseif ( $data['status'] == 'Finished' && $tasks[$id]['status'] == 'Pending'){
            $tasks[$id]['start_time'] = date("Y-m-d H:i:s", time());
            $tasks[$id]['end_time'] = date("Y-m-d H:i:s", time());
        }
        // if status has changed to 'Pending', sets 'start/end_time': NULL
        elseif ( $data['status'] == 'Pending' && $tasks[$id]['status'] != 'Pending'){
            $tasks[$id]['start_time'] = null;
            $tasks[$id]['end_time'] = null;
        }

        // updating task with new data
        $tasks[$id] = array_merge($tasks[$id], $data);

        return $this -> saveTasks($tasks);

    }


    ###############################################
    # HELPER FUNCTIONS                            #    
    ###############################################
    
    // method that saves an array containing all tasks to a json DataBase
    // after a task update or a create new task
    public function saveTasks(array $tasks): bool | string {

        foreach($tasks as $k => $task){
            $tasks_json[] = $task;
        }

        // save all tasks to json - repasar!
        $result = file_put_contents(ROOT_PATH.'/app/models/data/json/data.json', json_encode($tasks_json, JSON_PRETTY_PRINT));
        if (!$result){
            return "json update/insert failed";
        }
        return true;
    }
}