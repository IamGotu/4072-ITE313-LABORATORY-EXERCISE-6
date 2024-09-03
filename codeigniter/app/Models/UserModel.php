<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; //name of the database relation/table
    protected $allowedFields = ['username', 'email', 'password']; //allow columns for CRUD operation
    protected $UserTimestamps = true; //keywords of codeigniter
}