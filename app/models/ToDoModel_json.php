<?php

    class ToDoModel_json {
    
        public function updateTask($data, $id){
                
            $tasks = $this->getTasks();

            // si ha canviat l'estat a 'finished', posarem 'end_time' a la data/hora del canvi
            if ( $data['status'] == 'Finished' && $tasks[$id]['status'] != 'Finished'){
                $tasks[$id]['end_time'] = date("Y-m-d H:i:s", time());
            }
            // si ha canviat l'estat a 'Ongoing' i abans estava a 'finished', posarem 'end_time'en NULL
            elseif ( $data['status'] == 'Ongoing' && $tasks[$id]['status'] == 'Finished'){
                $tasks[$id]['end_time'] = "";
            }
            // si ha canviat l'estat a 'Pending' , posarem 'start/end_time' en NULL
            elseif ( $data['status'] == 'Ongoing' && $tasks[$id]['status'] != 'Ongoing'){
                $tasks[$id]['start_time'] = null;
                $tasks[$id]['end_time'] = null;
            }
            
            // actualitzem les dades amb la informació rebuda
            $tasks[$id] = array_merge($tasks[$id], $data);

            $this->saveTasks($tasks);

            $result = $this -> saveTasks_json($tasks);

            return $result;

        }

    }
    
?>