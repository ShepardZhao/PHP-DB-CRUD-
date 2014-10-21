PHP-DB-CRUD

This the the PHP DB MYSQL CONNECTION WITH CRUD METHOD.

How to use it? (MVC)

1. import DataBaseCRUDModel.php and DatabaseSingletonModel.php to your model folder.
2. change the "userName" and "Password" and "hostName" and "Dbname" to your own setting.


example:
  query:
  
        {
       $this->statement = 'SELECT family_id FROM iafamily_member WHERE member_user_id=?';
        $this->bindType = array('i');
        $this->bindName = array($userID);
        $this->selectSQL();
        return $this->selectedFetchResult;
      }
  

  insert or update or delete
    {

        $this->statement = 'INSERT INTO iafamily_member (family_id, member_user_id) VALUES(?,?)';
        $this->bindType = array('i','i');
        $this->bindName = array($this->familyId,$this->userID);

        //insert query
        $this->insertAndUpdateAndDeleteSQL();

        //bind result
        $step2= $this->affectedResult;
      }


more usage can check on  DataBaseCRUDModel.php file



