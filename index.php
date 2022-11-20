<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<?php

error_reporting(E_ERROR | E_PARSE);

class Question
{
    public $id;
    public $title;
    public $description;
    public $choices;
}

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

$questions = array();

for ($i = 1; $i <= 10; $i++) {
    $question = new Question;
    $question->id = $i;
    $question->title = "Question " . $i . ":";
    $question->description = "Eu nisl nunc mi ipsum faucibus. Praesent semper feugiat nibh sed pulvinar. Eu lobortis elementum nibh tellus molestie nunc.";
    $question->choices = array("A" => "Aliquam", "B" => "Bibendum", "C" => "Cras", "D" => "Donec");

    array_push($questions, $question);
}

function init_data()
{
    global $size;
    global $index;
    global $answers;
    global $maxIndex;
    global $questions;
    global $pagedQuestions;

    $maxIndex = ceil(count($questions) / $size);

    session_start();
    if (isset($_SESSION['index'])) {
        $index = $_SESSION['index'];
    }

    $pagedQuestions = paging($questions, $index, $size);

    if (isset($_SESSION['answers'])) {
        $answers = $_SESSION['answers'];
    }
}

function after_post()
{
    global $index;
    global $size;
    global $questions;
    global $pagedQuestions;

    session_start();
    if (isset($_SESSION['index'])) {
        $index = $_SESSION['index'];
        $pagedQuestions = paging($questions, $index, $size);
    }
}

function paging($array, $index, $size)
{
    return array_slice($array, ($index - 1) * $size, $size);
}

function update_answers($questions)
{
    global $answers;

    foreach ($questions as $question) {
        $answer = new Answer;
        $answer->id = $question->id;
        $answer->choice = $_POST["$question->id"];

        array_push($answers, $answer);
    }

    $_SESSION['answers'] = $answers;
}

$pagedQuestions = array();
$index = 1;
$size = 5;
$maxIndex;
$answers = array();

init_data();

if (isset($_POST['submit'])) {
    session_start();

    update_answers($pagedQuestions);

    header('Location: result.php');
}

if (isset($_POST['next'])) {
    session_start();

    update_answers($pagedQuestions);

    $_SESSION['index'] = $index + 1;
}

after_post();

?>

<body>
    <div class="container">

        <div class="shadow p-3 my-5 bg-body rounded text-center">
            <h2>
                Adipiscing enim eu turpis egestas pretium.
            </h2>
        </div>

        <form enctype="multipart/form-data" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <?php
            foreach ($pagedQuestions as $question) {
            ?>

                <div class="shadow p-3 my-5 bg-body rounded ">
                    <h4>
                        <?= $question->title ?>
                    </h4>

                    <div class="my-2">
                        <?= $question->description ?>
                    </div>

                    <div class="d-flex justify-content-between">

                        <?php
                        foreach ($question->choices as $key => $value) {
                            $radioId = "$question->id $key";
                        ?>
                            <div class="form-check">
                                <input id="<?= $radioId ?>" class="form-check-input" type="radio" name="<?= $question->id ?>" value="<?= $key ?>">
                                <label for="<?= $radioId ?>" class="form-check-label">
                                    <?= "$key. $value" ?>
                                </label>
                            </div>
                        <?php
                        }
                        ?>

                    </div>

                </div>

            <?php
            }
            ?>

            <div class="d-flex justify-content-center my-5">

                <?php
                if ($index < $maxIndex) {
                ?>
                    <input class="btn btn-primary" type='submit' name="next" value="Next">
                <?php
                } else {
                ?>
                    <input class="btn btn-primary" type='submit' name="submit" value="Nộp bài">
                <?php
                }
                ?>

            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>