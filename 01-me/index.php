<!DOCTYPE html>

<?php
$name = "Sepehr Soltanieh";
$algo = "SHA256";
$hash = hash($algo, $name);
?>

<head>
    <title>
        <?php echo $name ?> PHP
    </title>
</head>

<body>
    <h1>
        <?php echo $name ?> PHP
    </h1>

    <p>
        <?php echo "The $algo hash of \"$name\" is " ?>
        <code>
            <?php echo $hash ?>
        </code>
    </p>

    <h4>ASCII ART:</h4>
    <pre>
               |||||||
            |||||||||||||
        ||||||||     ||||||||
        |||||||       |||||||
        |||||||
         |||||||
           |||||||||
              |||||||||
                 |||||||||
                    |||||||
                     |||||||
         |||||       |||||||
         ||||||     |||||||
          |||||||||||||||
             |||||||||
    </pre>

    <ul>
        <li>View the <a href="check.php">check</a> page.</li>
        <li>View the <a href="check.php">fail</a> page.</li>
    </ul>

    <pre>
        <?php
        print_r($_GET);
        ?>
    </pre>
</body>