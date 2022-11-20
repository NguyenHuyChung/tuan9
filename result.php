<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<?php

error_reporting(E_ERROR | E_PARSE);

class Answer
{
    public $id;
    public $choice;
}

function console_log($data)
{
    $output = json_encode($data);

    echo "<script>console.log('{$output}' );</script>";
}

$abcd = array("A" => "A", "B" => "B", "C" => "C", "D" => "D");

function init_data()
{
    global $abcd;
    global $result;
    global $score;

    session_start();

    $answers = $_SESSION['answers'];

    console_log($answers);

    $expected = array();

    foreach ($answers as $answer) {
        $ans = new Answer;
        $ans->id = $answer->id;
        $ans->choice = array_rand($abcd);

        array_push($expected, $ans);
    }

    console_log($expected);



    foreach ($answers as $answer) {
        foreach ($expected as $value) {
            if ($answer->id == $value->id && $answer->choice == $value->choice) {
                $score += 1;
                break;
            }
        }
    }

    if ($score < 4) {
        $result = "Bạn quá kém, cần ôn tập thêm";
    } else if ($score >= 4 && $score <= 7) {
        $result = "Cũng bình thường";
    } else {
        $result = "Sắp sửa làm được trợ giảng lớp PHP";
    }

    session_destroy();
}

$result;
$score = 0;

init_data();

?>

<body>
    <div class="container">
        <div class="shadow p-3 my-5 bg-body rounded text-center">
            <h2>
                Điểm: <?= $score ?>
            </h2>
            <h2>
                <?= $result ?>
            </h2>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>