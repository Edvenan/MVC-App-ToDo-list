<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class ToDoController extends Controller 
{
	
    ###############################################
    # HOME:                                       #
    ###############################################
    // home method that will show main menu
    public function indexAction(){
    }


    ################################################
    # CRUD: CONTROLLER METHODS                     #    
    ################################################

    // CREATE TASK
    public function createTaskAction(){
        if (($_SERVER['REQUEST_METHOD'] == 'POST') &&  (!empty($_POST["name"])) && (!empty($_POST["author"]))) {

            //Recollim les noves dades
            $name = $_POST['name'];
            $author = $_POST['author'];

            $todo = $this-> setModel();
            
            //Les enviem al model
            $result = $todo -> createTask($name, $author);
            
            if (!$result){
                throw new Exception($result);

            } else {
                //Redirigim al llistat total
                header("Location: showAll");
            }
        
        } 
    }
    
    // READ ALL TASKS
	public function showAllTasksAction(){
        if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
            $_SESSION['db_type']= $_POST['db_type'];
        }

        $todo = $this-> setModel();
        $tasks = $todo->getTasks();
        $this->view->tasks = $tasks;
    }
    // READ TASK
    public function showTaskAction(){

        if (isset($_GET['id'])) {
            
            $taskId = $_GET['id'];

            $todo = $this-> setModel();
            
            $task = $todo->getTaskById($taskId);
            if(!$task) {
                throw new Exception("Task not found.");
            }  
            $this->view->task = $task;

        } else {
            echo "Not found.";
            exit;
        }
    }

	public function deleteTaskAction(){

        if(isset($_GET['id'])) {

            $todo = new ToDoModel();
            $taskId = $_GET['id'];
            $task = $todo->getTaskById($taskId);

            if(!$task) {
                echo "Task not found.";
                exit;
            }  else {  
                $todo -> deleteTask($taskId);
            }

            header("Location: showAll");

        } else {
            echo "Not found.";
            exit;
        } 
        
    }

	/*public function searchTaskAction(){
        $this->view->message = "TO-DO App - SEARCH TASK VIEW!!!!";
    }*/


    // UPDATE TASK
    public function updateTaskAction(){

        $this -> showTaskAction();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ((!empty($_POST["name"])) && (!empty($_POST["author"]))) {

                $taskId = $_GET['id'];

                // receiving new data
                $newData['name']   = $_POST['name'];
                $newData['status'] = $_POST['status'];
                $newData['author'] = $_POST['author'];

                // sending new data to Model 
                $todo = $this-> setModel();

                $result = $todo -> updateTask($newData, $taskId);
                
                // receiving results from model
                if (!$result){
                    throw new Exception("Update failed.");

                } else {
                    // redirecting to tasks list
                    header("Location: showAll");
                    exit;
                }

            } elseif((empty($_POST["name"])) OR (empty($_POST["author"]))) {

                throw new Exception("Name and Author fields are required.");

            }

        }


    }

    ###############################################
    # HELPER FUNCTIONS                            #
    ###############################################

    // helper function
    public function setModel(): Object {
        switch ($_SESSION['db_type']){

            case "json":
                return new ToDoModel_json();
            case "mysql":
                return new ToDoModel_mysql();
            case "mongodb":
                return new ToDoModel_mongo();
            default:
            throw new Exception("Wrong DataBase!");
        }
    }

}