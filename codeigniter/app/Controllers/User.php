<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class User extends Controller
{
    public function register()
    {
        return view('register'); //register is file name in App\View\register.php
    }

    //store data in database from post form
    public function store()
    {
        $userModel = new UserModel(); //call user model
        //get data from post form
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        //store them in data array to save by model
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => password_hash((string)$password,PASSWORD_DEFAULT) //hashed password for security
        ];

        $userModel->save($data);

        return redirect()->to('/login'); 
    }

    //login iu
    public function login()
    {
        return view('login');
    }

    //verify login
    public function verifylogin()
    {

        //call model
        $userModel = new UserModel();
        //get data from post form
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        //check user in database if account exist or not
        $data = $userModel->where('username',$username)->first();
        if($data){

            //set session for store login success
            session()->set([
                'id'=>$data['id'],
                'username'=>$data['username'],
                'logged_in'=>true
            ]);

            //return to home page
            return redirect()->to('/');
        }else{
            return redirect()->to('/login');
        }
    }

    //logout function
    public function logout()
    {
        //destroy all session
        session()->destroy();
        //redirect to login page
        return redirect()->to('/login');
    }

}