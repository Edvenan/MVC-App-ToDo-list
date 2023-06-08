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

        $result = $this->save($new_task);
        // error handling
        if(!$result){
            return ("CreateTask-Model: Save(): MySQL insert failed");
        }

        return true;
    }

    // READ: method that returns an array containing all tasks in MySQL DataBase
    public function getTasks(): array | string {

        // SQL query
        $sql = 'select * from ' . $this->_table;
        $statement = $this->_dbh->prepare($sql);
        $statement->execute();
        // store all returned rows in array of stdClass objects
        $result = $statement->fetchAll(PDO::FETCH_OBJ);

        // Convert stdClass objects to associative arrays
        $tasks = json_decode(json_encode($result), true);
        
        return $tasks;
    }
    
    // READ: method that gets a task by its 'id' from MySQL DataBase
    public function getTaskById($id){

        // returns one task by its $id
        $result = $this->fetchOne($id);

        // Convert stdClass objects to associative arrays
        $task = json_decode(json_encode($result), true);

        return $task;
    }

     // UPDATE: method that updates a task in MySQL DataBase
     public function updateTask($data, $id): bool | string {
        
        $original_task = $this->getTaskById($id);
        // error handling
        if (is_string($original_task)){
            return "UpdateTask-Model: ".$original_task;
        }

        // si ha canviat l'estat a 'finished', posarem 'end_time' a la data/hora del canvi
        if ( $data['status'] == 'Finished' && $original_task['status'] != 'Finished'){
            $original_task['end_time'] = date("Y-m-d H:i:s", time());
        }
        // si ha canviat l'estat a 'Ongoing' posarem 'start_date' a l'hora del canvi i posarem 'end_time'en NULL
        elseif ( $data['status'] == 'Ongoing' && $original_task['status'] != 'Ongoing'){
            $original_task['start_time'] = date("Y-m-d H:i:s", time());
            $original_task['end_time'] = null;
        }
        // si ha canviat l'estat a 'Pending' , posarem 'start/end_time' en NULL
        elseif ( $data['status'] == 'Pending' && $original_task['status'] != 'Pending'){
            $original_task['start_time'] = null;
            $original_task['end_time'] = null;
        }

        $updated_task = array_merge($original_task, $data);

        $result = $this->save($updated_task);
        // error handling
        if(!$result){
            return ("UpdateTask-Model: Save(): MySQL set failed");
        }
        return true;
 
    }
    
    // DELETE: method that deletes a task from MySQL DataBase
    public function deleteTask(int $id): bool {

        $task = $this -> getTaskById($id);

        if(!$task) {
            return false;  
        }  

        return $this->delete($id);

    } 
    
}