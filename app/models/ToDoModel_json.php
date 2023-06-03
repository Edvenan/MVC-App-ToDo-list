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

        $task['id'] = $new_taskId;
        $task['name'] = $name;
        $task['start_time'] = null;
        $task['end_time'] = null;
        $task['author'] = $author;
        $task['status'] = 'Pending';

        // add the new task object to all tasks
        $tasks[] = $task;

        return $this->saveTasks($tasks);

    }
    

}