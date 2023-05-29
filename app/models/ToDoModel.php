<?php

// define enum type
enum taskState{
    case Pending;
    case Ongoing;
    case Finished;
}

// define Task class
class Task{
    
    private static $id_counter = 1; // we create a static task counter that will be used to set the 'id' of every new task
    public int $id;
    public string $name;
    public string $start_time;
    public string $end_time;
    public taskState $status;
    public string $author;

    public function __construct(string $name, string $author){
        // constructor sets the task id
        $this->id = Task::$id_counter;
        Task::$id_counter ++;
        $this->name = $name;
        // constructor sets the start date
        $this->start_time = date("Y-m-d, G:i:s", time());
        $this->end_time = "";
        // constructor sets the status
        $this->status = taskState::Pending;
        $this->author = $author;
    }
}


// helper function that returns all tasks from json file
function readTasks_json(): array{

    (string) $jsonFile = file_get_contents(ROOT_PATH.'/app/models/data/data.json');
    (array) $tasks = json_decode($jsonFile, true);  // returns array of task objects

    return $tasks;
}

// helper function to save all tasks into a json file
function saveTasks_json(array $tasks): bool {
    
     // save all tasks to json
     return file_put_contents(ROOT_PATH.'/app/models/data/data.json', json_encode($tasks));

}


// define ToDoModel class
class ToDoModel{


    // method that returns an array containing all tasks
    public function getTasks(): array{
        (array) $tasks = readTasks_json();
        return $tasks;
    }

    public function createTask(string $name, string $author){

        // get all tasks
        $tasks = $this->getTasks();
        // instantiate a new Task object
        $task = new Task($name, $author);
        // add the new task object to all tasks
        $tasks[] = $task;

        $saved = saveTasks_json($tasks);
        if (!$saved){
            echo "Error saving json file!!";
        };

    }

    public function getTaskById($id){
        
        $tasks = $this->getTasks();

        foreach($tasks as $task) {
    
            if ($task['id'] == $id) {
              return $task;
            }
      
        }
      
        return null;
        
    }
    
    public function updateTask($data, $id){
        
        $tasks = $this->getTasks();

        $tasks[$id] = array_merge($tasks[$id], $data);

        $result= saveTasks_json($tasks);

        //Falta control d'errors
        //if $result = 0 {}}
        
        //Tests realizats
        /*echo '<pre>';
        var_dump($tasks[$id]);
        echo '<pre>';*/

        /*echo '<pre>';
        var_dump($tasks);
        echo '<pre>';*/
 
    }

    public function deleteTask($id){

        $tasks = $this->getTasks();

        unset($tasks[$id]);

        saveTasks_json($tasks);

        //Falta control d'errors
        //if $result = 0 {}}

    }  



}