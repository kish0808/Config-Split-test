<?php


namespace Drupal\d8_activity;


use Drupal\Core\Database\Connection;



class DicUserEntry{


    private $database;



    public function __construct(Connection $Connection )
    {
        $this->database = $Connection;
    }



    public function read(){

        return $this->database->select('mydata', 'x')
            ->fields('x', ['first_name'])
            ->range(0, 1)
            ->execute()->fetchField();

    }


    public function write( $userdata){
        $this->database->insert('mydata')
            ->fields(['first_name','last_name'])
            ->values([
                'first_name'=> $userdata['first_name'],
                'last_name' => $userdata['last_name'],
            ])
            ->execute();
    }

}