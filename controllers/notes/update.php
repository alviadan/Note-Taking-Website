<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);

$currentUserId = 21;

// find the corrresponding note
$note = $db->query('select * from notes where id = :id', [
    'id' => $_POST['id'],
])->findOrFail();

// authorize the user
authorize($note['user_id'] === $currentUserId);

// validate the form
$errors = [];

if(! Validator::string($_POST['title'], 1, 1000)){
    $errors['title'] = 'A title of no more than 1,000 characters is required';
}

// if no validation errors, update ther record in the notes database table.
if(count($errors)){
    return view('notes/edit.view.php', [
        'heading' => 'Edit Note',
        'errors' => $errors,
        'note' => $note,
    ]);
}

$db->query('update notes set title = :title where id = :id', [
    'id' => $_POST['id'],
    'title' => $_POST['title'],
]);

// redirect user
header('location: /notes');
die();