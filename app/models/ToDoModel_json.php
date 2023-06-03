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
    

    ###############################################
    # HELPER FUNCTIONS                            #    
    ###############################################
    
    // method that saves an array containing all tasks to a json DataBase
    // after a task update or a create new task
    public function saveTasks(array $tasks): bool | string {

        foreach($tasks as $k => $task){
            $tasks_json[] = $task;
        }

        // save all tasks to json
        $result = file_put_contents(ROOT_PATH.'/app/models/data/json/data.json', json_encode($tasks_json, JSON_PRETTY_PRINT));
        if (!$result){
            return "json update/insert failed";
        }
        return true;
    }
}