<?php
include './includes/connect.php';

$stmt2 = $con->prepare("SELECT * FROM QuizAnswers WHERE QuestionID = ?");
$stmt2->execute(array(filter_var($_GET['ID'], FILTER_SANITIZE_NUMBER_INT)));
$answers = $stmt2->fetchAll();

echo json_encode($answers);
?>