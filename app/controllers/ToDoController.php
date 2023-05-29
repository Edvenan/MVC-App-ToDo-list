<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class ToDoController extends Controller 
{
	
    // home method that will show main menu
    public function indexAction(){
        $this->view->message = "TO-DO App - HOME VIEW!!!!";
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

	public function createTaskAction(){
        $this->view->message = "TO-DO App - CREATE TASK VIEW!!!!";
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

	public function updateTaskAction(){
        
        $this -> showTaskAction();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $todo = new ToDoModel();
            $taskId = $_GET['id'];

            //Recollim les noves dades
            $newData = $_POST;

            //Les enviem al model
            $todo -> updateTask($newData, $taskId);
             
            //Redirigim al llistat total
            header("Location: showAll");
            exit;
            
            //tests
            /*echo '<pre>';
            var_dump($task);
            echo '<pre>';

            echo '<pre>';
            var_dump($_POST);
            echo '<pre>';*/
        
        } 
    }
}
