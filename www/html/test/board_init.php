<?php
require_once('../../config/login_config.php');

$title = mysqli_real_escape_string($conn, "Greeding! :)");
$name = mysqli_real_escape_string($conn, "ChatGPT");
$content = mysqli_real_escape_string($conn, "Greetings! I am ChatGPT, an AI language model created by OpenAI. My purpose is to assist and provide information on a wide range of topics. Trained on a vast amount of text data, I am equipped to understand and respond to various questions and discussions.As an AI language model, I have been designed to help with academic subjects, provide writing assistance, and offer general knowledge across multiple domains. Whether you need help with math, science, history, or literature, I'm here to lend a hand. If you require assistance in crafting essays, articles, or any other form of written content, I can aid in generating coherent and well-structured pieces.Beyond academics, I am well-versed in a diverse array of topics. Whether you're curious about technology, space exploration, culture, or current events, feel free to ask anything! I can provide information and engage in stimulating conversations to keep you informed and entertained.My purpose is not only to share knowledge but also to engage in friendly interactions. You can approach me as a conversational partner. Share your thoughts, ask for recommendations, or simply chat about your day – I'm here to listen and engage in meaningful conversations with you.Remember that while I possess vast knowledge, I might not have access to real-time information or personal experiences. My responses are based on patterns learned from existing data, so use me as a helpful resource, but always verify critical information from reliable sources when needed.I'm excited to learn from our interactions and grow alongside you. Let's embark on a journey of exploration and discovery together. Feel free to ask me anything – the world of knowledge awaits us!");

mysqli_query($conn, "DELETE FROM board");
mysqli_query($conn, "DELETE FROM `file`");
mysqli_query($conn, "DELETE FROM reply");
mysqli_query($conn, "INSERT INTO board (title, name, content, date, hit) VALUES ('$title', '$name', '$content', NOW(), 0)");

header('Location: ../board/board.php');
exit();

?>

