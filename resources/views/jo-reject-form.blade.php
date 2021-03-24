<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Order Portal | Reject</title>
    <style>
        .card { 
            margin:auto;
            width:50%;
            background-color:#f6f6f6;
            padding:20px;
        }

        body {
            font-family: Arial, Sans-serif, Roboto;

        }

        button {
			padding:1em 2em 1em 2em;
			background-color:#DD4B39 !important;
			border-color:#DD4B39 !important;
			color: #fff;
			text-decoration: none;
		}



    </style>
</head>
<body>
    <div class="card">
        <h3>Reject job order request no. <?php echo $job_order_id; ?></h3>
        <p for="">Please state your reason:</p>
        <form action="<?php echo $reject_api;?>" method="post">
            <input type="hidden" name="approval_id" value="<?php echo $approval_id;?>" />
            <textarea required rows="10" cols="20" style="width:100%;" name="remarks"></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>