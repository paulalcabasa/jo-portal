<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title;?></title>
    <style>
        .card { 
            margin:auto;
            width:60%;
            background-color:#f6f6f6;
            padding:10px;
            text-align:center;
        }
        
        body {
            font-family: Arial, Sans-serif, Roboto;

        }
    </style>
</head>
<body>
    <div class="card">
        <h1><?php echo $title; ?></h1>
        <blockquote><?php echo $message; ?></blockquote>
    </div>
</body>
</html>