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
    public function showTaskAction(){

        if(isset($_GET['id'])) {

            $todo = new ToDoModel();
            $taskId = $_GET['id'];
            $task = $todo->getTaskById($taskId);
            if(!$task) {
                echo "Task not found.";
                exit;
            } 

            $this->view->task = $task;

        } else {
            echo "Sorry, not found.";
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

	public function showAllTasksAction(){
        $todo = new ToDoModel();
        $tasks = $todo->getTasks();
        if(!$tasks) {
            echo "Tasks not found.";
            exit;
        } 
        $this->view->tasks = $tasks;
    }

    // UPDATE TASK
    public function updateTaskAction(){

        $this -> showTaskAction();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ((!empty($_POST["name"])) && (!empty($_POST["author"]))) {

                $taskId = $_GET['id'];

                //Recollim les noves dades
                $newData['name']   = $_POST['name'];
                $newData['status'] = $_POST['status'];
                $newData['author'] = $_POST['author'];

                //Les enviem al model
                $todo = $this-> setModel();

                $result = $todo -> updateTask($newData, $taskId);

                if (!$result){
                    throw new Exception("Update failed.");

                } else {
                    //Redirigim al llistat total
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