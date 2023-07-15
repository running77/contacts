<?php
session_start();
error_reporting(55);
require "lib/classContacts.php";

$contact = new Contacts();
$contact->layout = 'templates/template.html';


switch ($_GET['act']) {

    case "":
        $contact->ListContacts();
        break;
    case "add":
        $contact->AddContact();
        break;
    case "edit":
        $contact->EditContact();
        break;
    case "delete":
        $contact->DeleteContact();
        break;
}
