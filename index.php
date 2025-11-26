<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Demo</title>
    <link rel="icon" type="image/x-icon" href="public/favicon.ico">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f5f6fa;
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            width: 460px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            margin-bottom: 18px;
            color: #333;
        }

        .quote {
            font-size: 22px;
            font-weight: bold;
            color: #555;
        }

        footer {
            margin-top: 25px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>

<body>

    <?php
    $name = "48 Laws of Power";
    ?>

    <div class="card">
        <h1>Book Reader</h1>

        <p class="quote">
            You have read: "<?php echo $name; ?>"
        </p>

        <?php include 'src/main.php'; ?>

        <footer>
            © <?php echo date("Y"); ?> Iconic University — PHP Training
        </footer>
    </div>

</body>

</html>