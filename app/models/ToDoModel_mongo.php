<?php

// include Composer's autoloader
require VENDOR_PATH.'autoload.php'; 

/**
 * ToDo model for the application.
 * Handles access to MongoDB Data Base.
 */
class ToDoModel_mongo {

    protected $_dbh = null;
    protected $_collection = "";

    public function __construct() {
		// parses the settings file
		$settings = parse_ini_file(ROOT_PATH . '/config/settings.ini', true);
		
		// starts the connection to the database
        $this->_dbh = new MongoDB\Client('mongodb://localhost:27017');
		
        #$this->_collection = $this->_dbh->$settings['mongodb']['dbname']->$settings['mongodb']['collection'];
        $this->_collection = $this->_dbh->todo->tasks;
	}


    ################################################
    # CRUD: CLASS METHODS TO OPERATE WITH DATABASE #    
    ################################################

    // CREATE: method that creates a task and adds it to the MongoDB DataBase
    public function createTask(string $name, string $author): bool | string {

        // get all tasks
        $tasks = $this->getTasks();

        // get highest id in DB to determine new id
        $highestId = -1; // Initialize with a value lower than any possible id
        foreach ($tasks as $task) {
            if ($task['id'] > $highestId) {
                $highestId = $task['id'];
            }
        }
        $newId = $highestId + 1;

        // build next taks
        $new_task['id'] = $newId;
        $new_task['name'] = $name;
        $new_task['status'] = 'Pending';
        $new_task['start_time'] = null;
        $new_task['end_time'] = null;
        $new_task['author'] = $author;

        $result = $this->_collection->insertOne( $new_task );

        if (!$result->getInsertedCount()){
            return "MongoDB insert failed";
        }
        return true;
    }

}