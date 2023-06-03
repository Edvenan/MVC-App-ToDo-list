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
    
}