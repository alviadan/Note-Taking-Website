<?php

use Core\Database;
use Core\App;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];

// validate the form inputs.
$errors = [];

if(!Validator::email($email)){
    $errors['email'] = 'Please provide a valid email address.';
}

if(!Validator::string($password, 7, 255)){
    $errors['password'] = 'Please provide a valid password';
}

if(! empty($errors)){
    return view('registration/create.view.php', [
        'errors' => $errors
    ]);
}

$db = App::resolve(Database::class);
// check if the account already exists
$user = $db->query('select * from users where email = :email', [
    'email' => $email,
])->find();

// yes -> login
if($user){
    header('location: /');
    exit();
} else {
// no -> store db -> log in -> redirect
    $db->query('INSERT INTO users(email, password) VALUES(:email, :password)', [
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
    ]);

    $_SESSION['user'] = [
        'email' => $email
    ];

    header('location: /');
    exit();
}