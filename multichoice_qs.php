<?php
/*
http://localhost/code_qs/multichoice_qs.php?q=2

https://www.sanfoundry.com/1000-php-questions-answers/
*/

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$questions = [
	'What is the value of x?|x = 3 * 3 ** 3|9,27,81,729|81',
	'What is the value of x?|x = 5\nx *= 2 + 1|10,15,25,35|15',
	'What is the value of x?|x = 5 % 2|2,2.5,1,0.5|1',
	'What will the following PHP code output?|echo (10 == "10");|True,False,Error,None|True',
	'What will the following PHP code output?|$a = 5;\n$b = 3;\necho(\'$a + $b = \' . $a + $b);|8,$a + $b = 8,5 + 3 = 8,$a + $b = 5 + 3|$a + $b = 8',
	'What will the following Python code output?|print("Hello" + 3 * " World")|Hello World,Hello World World World,Hello3World,Hello World3|Hello World World World',
	'What will the following Python code output?|a = "10"\nb = 3\nprint(int(a) % b)|1,0,3,10|1',
	'If 1708410000 equals 2024-02-20 06:20 what will the timestamp value be for 2024-02-20 07:20?||1708406400,1708410000,1708413600,1708417200|1708413600',
	'What will the following Python code output?|print(10 // 3)|3,3.0,3.333333333,Error|3',
	'What will the following PHP code output?|$fruits = ["apple", "orange", ["pear", "mango"],"banana"];\necho (count($fruits, 1));|6,5,4,3|6',
	'What will the following PHP code output?|function multi($num)\n{\n   if ($num == 3) {echo "I Wonder";}\n    if ($num == 7) {echo "Which One";}\n    if ($num == 8) {echo "Is The";}\n    if ($num == 19) {echo "Correct Answer";}\n}\n$can = stripos("I love php, I love php too!","PHP");\nmulti($can);|Correct Answer,Is The,I Wonder,Which One|Which One',
	'What will the following PHP code output?|$url = "phpmcq@sanfoundry.com";\necho ltrim(strstr($url, "@"),"@");|phpmcq@sanfoundry.com,php@sanfoundry.com,phpmcq@,sanfoundry.com|sanfoundry.com',
	'What will the following PHP code output?|$x = 5;\n$y = 10;\nfunction fun()\n{\n    $y = $GLOBALS[\'x\'] + $GLOBALS[\'y\'];\n}\nfun();\necho $y;|5,10,15,Error|10',
	'What does PDO stand for?||PHP Database Orientation,PHP Data Orientation,PHP Data Object,PHP Database Object|PHP Data Object',
	'What will the following PHP code output?|$data = [\n    \'x\' => 4,\n    \'y\' => 3,\n    \'z\' => 1,\n];\n$data[\'z\'] = $data[\'z\'] + $data[\'x\'] + $data[\'y\'];\necho "$data[\'z\']";|15,8,1,Error|Error',
];
$total_questions = count($questions);

// echo '<pre style="background:#111; color:#b5ce28; font-size:11px;">'; print_r($_POST); echo '</pre>';

$q = 1;
$total = 1;
$start_time = '';
$incorrect = isset($_POST['incorrect']) ? $_POST['incorrect'] : '';

if (isset($_POST['submit_answer'])) {
	$start_time = $_POST['start_time'];
	
	if ($_POST['q'] == $total_questions) {
		$total = $_POST['total'];
		$interviewee = $_POST['interviewee'];
		$duration = time() - $start_time; // total seconds
		$incorrect = ltrim($_POST['incorrect'], ',');
		
		file_put_contents('multichoice_results.txt', "$interviewee|$total/$total_questions|{$duration}s|$incorrect\n", FILE_APPEND);
		echo "<p style='font-size:44px; padding-left:40px; padding-top:40px;'>FINISH</p>"; die();
	}
	
	$answer = $_POST['ans'];
	$choice = $_POST['choice'];
	$cur_total = $_POST['total'];
	$interviewee = $_POST['interviewee'];
	
	if ($answer == $choice) {
		$total = $cur_total +1;
	}
	else {
		$incorrect = $_POST['incorrect'].','.$_POST['q'];
		$total = $cur_total;
		
		echo '<pre style="background:#111; color:#b5ce28; font-size:11px;">'; print_r($incorrect); echo '</pre>';
	}
	
	$q = $_POST['q'] +1;
}

$ques = isset($_POST['q']) ? $questions[$_POST['q']] : $questions[0];
list($qs, $code, $options, $ans) = explode('|', $ques);
$multi_choice = explode(',', $options);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Coding Questions</title>

<style>
	* {
		font-family: monospace;
		font-size: 20px;
	}
	body {
		padding: 20px;
	}
	pre {
		display: inline-block;
		width: 800px;
		background: #222;
		color: #0f0;
		border-radius: 6px;
		padding: 8px;
	}
</style>

</head>
<body>

<!-- Question -->
<?= $qs ?> (<?= "$q/$total_questions" ?>)<br>

<!-- Code -->
<?php if ($code) { ?>
<pre><?= str_replace('\n', "<br>", $code) ?></pre>
<?php } else {echo "<br>";} ?>

<!-- Multiple choice answers -->
<form method="post" id="mc_form">
<input type="hidden" name="submit_answer">
<input type="hidden" name="q" value="<?= $q ?>">
<input type="hidden" name="ans" value="<?= $ans ?>">
<input type="hidden" name="total" value="<?= $total ?>">
<input type="hidden" name="incorrect" value="<?= $incorrect ?>">
<input type="hidden" id="start_time" name="start_time" value="<?= $start_time ?>">
<?php if (!isset($interviewee)) { ?>
<div id="options" style="visibility: hidden;">
<?php } else { ?>
<div id="options">
<?php } ?>
<?php foreach ($multi_choice as $i => $val) { ?>
	<input type="radio" id="<?= $i ?>" name="choice" value="<?= $val ?>" onclick="this.form.submit()"> <label style="display: inline-block; width: 300px;" for="<?= $i ?>"> <?= $val ?></label><br>
<?php } ?>
</div>
<?php if (!isset($_POST['interviewee'])) { ?>
<br><input type="text" id="interviewee_start" name="interviewee" value="" placeholder="Enter Name" autocomplete="off">
<?php } else { ?>
	<input type="hidden" id="interviewee" name="interviewee" value="<?= $interviewee ?>">
<?php } ?>
</form>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
$(function() {
	// Disable form submission - users may press 'Enter' after entering their name.
	$('#mc_form').submit(function(e) {
		e.preventDefault();
	});
	
	$('#interviewee_start').focus();
	
	$('#interviewee_start').on('input', function(){
		var intervieweeValue = $(this).val().trim();
		if(intervieweeValue !== '' && intervieweeValue.length > 2) {
			$('#options').css('visibility', 'visible');
			$('#start_time').val('<?= time() ?>')
		}
		else {
			$('#options').css('visibility', 'hidden');
		}
	});
});
</script>
</body>
</html>