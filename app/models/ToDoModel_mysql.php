<?php

/**
 * ToDo model for the application.
 * Handles access to MySQL Data Base.
 */
class ToDoModel_mysql extends Model{

    // set the table we want to look into
    protected $_table = 'tasks';

    ################################################
    # CRUD: CLASS METHODS TO OPERATE WITH DATABASE #    
    ################################################

    // CREATE: method that creates a task and adds it to the MySQL DataBase
    public function createTask(string $name, string $author): bool | string {

        $new_task['name'] = $name;
        $new_task['start_time'] = null;
        $new_task['end_time'] = null;
        $new_task['author'] = $author;
        $new_task['status'] = 'Pending';

        return $this->save($new_task);
    }

    // UPDATE: method that updates a task and saves the changes
    public function updateTask(array $data, int $id): bool {
        
        $original_task = $this->getTaskById($id);

        // if status has changed to 'finished', sets 'end_time': CURRENT DATE AND TIME
        if ( $data['status'] == 'Finished' && $original_task['status'] != 'Finished'){
            $original_task['end_time'] = date("Y-m-d H:i:s", time());
        }
        // if status has changed to 'Ongoing' from 'finished', sets 'end_time': NULL
        elseif ( $data['status'] == 'Ongoing' && $original_task['status'] == 'Finished'){
            $original_task['end_time'] = null;
        }
        // if status has changed to 'Pending', sets 'start/end_time': NULL
        elseif ( $data['status'] == 'Ongoing' && $original_task['status'] != 'Ongoing'){
            $original_task['start_time'] = null;
            $original_task['end_time'] = null;
        }

        // updating task with new data
        $updated_task = array_merge($original_task, $data);

        return $this->save($updated_task);
    }
    
}