<?php
/**
 *
 Copyright (c) <2014> Shepard

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
 * Date: 4/08/14
 * Time: 4:41 PM
 */

 class DatabaseSingletonModel{
     private $_connection;
     private static $_instance; //The single instance
     private $_host = HostName; // here to input your HOSTNAME here
     private $_username = UserName; // here to input your UserName here
     private $_password = Password; // here to input your password here
     private $_database = Database; // here to input your DB name

     /*
     Get an instance of the Database
     @return Instance
     */
     public static function getInstance() {
         if(!self::$_instance) { // If no instance then make one
             self::$_instance = new self();
         }
         return self::$_instance;
     }

     // Constructor
     private function __construct() {
         $this->_connection = new mysqli($this->_host, $this->_username,
             $this->_password, $this->_database);

         // Error handling
         if(mysqli_connect_error()) {
             trigger_error("Failed to connect to MySQL: " . mysql_connect_error(),
                 E_USER_ERROR);
         }
     }

     // Magic method clone is empty to prevent duplication of connection
     private function __clone() { }

     // Get mysqli connection
     public function getConnection() {
         return $this->_connection;
     }





 }