<?php
/**
 * Copyright (c) <2014> Shepard

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
 * you are free to use this class but you have to keep the top copyright
 * User: Shepard
 * Date: 9/09/14
 * Time: 2:10 PM
 */
//include database singleton
include_once 'DatabaseSingletonModel.php';
class DataBaseCRUDModel {

    protected  $singletonDbConnection;
    protected  $statement; //prepare statement
    protected  $bindName; // what variables will be bound
    protected  $bindType=array(); //type of binding, such as 'iss'
    protected  $selectedFetchResult=array(); //result that query from the dataBase, it is an array type
    protected  $affectedResult;// this is only affected from insert, update and delete SQL query


    /**
     * DB CRUD
     * $statement for SQL query language
     * $condition such as i,s,i
     * $bindType such as $variable, which has to be corresponding to $condition
     */

    protected function __construct(){
        $this->singletonDbConnection = DatabaseSingletonModel::getInstance()->getConnection();
    }

    //insert and update and delete
    protected function insertAndUpdateAndDeleteSQL(){

        if($stmt=$this->singletonDbConnection->prepare($this->statement)){
            call_user_func_array(array($stmt, 'bind_param'), $this->getDynamicBind());
            $stmt->execute();
            $this->affectedResult = $stmt->affected_rows;
            $stmt->close();

        }
        else{
            $this->affectedResult = null;
        }
    }


    //select - query
    protected function selectSQL(){

        if($stmt=$this->singletonDbConnection->prepare($this->statement)){
            //dynamic binding parameters
            call_user_func_array(array($stmt, 'bind_param'), $this->getDynamicBind());
            $stmt->execute();
            $meta = $stmt->result_metadata();
            $fields = $results = array();

            // This is the tricky bit dynamically creating an array of variables to use
            // to bind the results
            while ($field = $meta->fetch_field()) {
                $var = $field->name;
                $$var = null;
                $fields[$var] = &$$var;
            }

            // Bind Results
            call_user_func_array(array($stmt,'bind_result'),$fields);

            // Fetch Results
            $i = 0;
            while ($stmt->fetch()) {
                $results[$i] = array();
                foreach($fields as $k => $v)
                    $results[$i][$k] = $v;
                $i++;
            }


            //copy result to $selectedFetchResult
            $this->selectedFetchResult = $results;

            $stmt->close();
        }
        else{
            $this->selectedFetchResult = null;
        }
    }

    /**
     * end
     */



    /**
     * return dynamic binding
     *
     */

    private function getDynamicBind(){
        $a_params = array();

        $param_type = '';
        $n = count($this->bindType);
        for($i = 0; $i < $n; $i++) {
            $param_type .= $this->bindType[$i];
        }

        /* with call_user_func_array, array params must be passed by reference */
        $a_params[] = & $param_type;

        for($i = 0; $i < $n; $i++) {
            /* with call_user_func_array, array params must be passed by reference */
            $a_params[] = & $this->bindName[$i];
        }
        return $a_params;


    }

    /**
     * end
     */




    /**
     * close db connection
     */
    protected function closeDebConnection(){
        $this->singletonDbConnection->close();
    }

    /**
     * end
     */




} 