<?php

session_start();
if (!isset($_SESSION['logged_in'])) {
    echo "<script>window.location='index.php';</script>";
    exit();
}
include "conn.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Trabalhos</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="./js/xlsx.full.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #212529;
            color: white;
            transition: 0.3s;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            transition: 0.3s;
        }

        .navbar {
            background: #f8f9fa;
        }

        .card {
            transition: 0.3s;
        }

        .card1:hover {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .sidebar a span {
                display: none;
            }

            .content {
                margin-left: 60px;
            }
        }
    </style>
</head>

<body class="bg-light">

    <?php include "include/sidebar.php"; ?>