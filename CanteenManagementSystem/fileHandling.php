<html>

<head>
    <title>...</title>
    <style type="text/css">
    body{
        background-color: lightcyan;
    }
    pre{
        background-color: black;
        color: white;
    }
    h1{
        background-color:lightgreen;
    }
    p{
        background-color: aqua;
    }

       
    </style>
</head>

<body>
    <?php

    if (file_exists('help.txt')) {
        echo "<BR> <h1>file exists !";
        if (filesize('help.txt') == 0) {
            echo "<BR> file exists,but empty";
        } else {
            echo "<BR><p> file exists, with ", filesize("help.txt"), "Bytes size: ";
            $fh = fopen("help.txt", "r");
            $stats = fstat($fh);
            echo "<BR> file info: <BR>";
            print_r($stats);
            echo "<BR> reading help text: <BR><pre><b><i><h2>", readfile("help.txt");
        }
    } else {
        echo "<BR> file does not exists !";
    }

    ?>
</body>

</html>