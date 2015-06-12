<!DOCTYPE html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0;" />
	<title>Loan Payment Calculator</title>
	<style type="text/css">
	html, body{
		padding: 0;
		margin: 0;
		font: normal 12px arial;
		width: 800px;
	}
	#payment{
		background: linear-gradient(#F5F5F5, #DDD);
		padding: 10px 5px;
		border-radius: 4px;
		border-bottom: 1px solid #CCC;
		text-shadow: 0px 1px #FFF;
		color: #A00;
		font-weight: bold;
		margin: 10px 0;
	}
	</style>
</head>
<body>
<form method="post" action="">
<table border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td><label>Loan Amount ($):</label></td>
		<td><input type="text" name="amount_dollars" size="6" value="<?php echo (isset($_POST['amount_dollars']) ? $_POST['amount_dollars'] : NULL) ?>" /> . <input type="text" name="amount_cents" size="2" maxlength="2" value="<?php echo (isset($_POST['amount_cents']) ? $_POST['amount_cents'] : NULL) ?>"/></td>
	</tr>
	<tr>
		<td><label>Interest Rate (%):</label></td>
		<td><input type="text" name="rate" size="4" maxlength="4" value="<?php echo (isset($_POST['rate']) ? $_POST['rate'] : NULL); ?>" /></td>
	</tr>
	<tr>
		<td><label>Down Payment ($):</label></td>
		<td><input type="text" name="dp_dollars" size="6" value="<?php echo (isset($_POST['dp_dollars']) ? $_POST['dp_dollars'] : NULL); ?>" /> . <input type="text" name="dp_cents" size="2" maxlength="2" value="<?php echo (isset($_POST['dp_cents']) ? $_POST['dp_cents'] : NULL); ?>" /></td>
	</tr>
	<tr>
		<td>Term (m):</td>
		<td><input type="text" name="term" value="<?php echo (isset($_POST['term']) ? $_POST['term'] : NULL); ?>"  size="3" /></td>
	</tr>
	<tr>
		<td>Starting Date</td>
		<td>
			<select name="month">
			<?php
			$months = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
			foreach($months as $key => $value){
				$date = new dateTime('now');
				$selected = (isset($_POST['month']) ? ($_POST['month'] == $key ? 'selected' : NULL) : ($date->format('m') == $key ? 'selected' : NULL));
				echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
			}
			?>
			</select>
			<input type="text" name="year" value="<?php echo $date->format('Y'); ?>" size="4" maxlength="4" />
		</td>
	</tr>
	<tr>
		<th colspan="2"><input type="submit" value="Calculate" name="calculate" /></th>
	</tr>
</table>
<?php
if(isset($_POST['calculate'])){
	$principal = ($_POST['amount_dollars']. '.' . $_POST['amount_cents']) - ($_POST['dp_dollars'] . '.' . $_POST['dp_cents']);
	$rate = ($_POST['rate'] / 12) / 100;
	$term = $_POST['term'];

	$payment = $principal * ($rate  / (1 - pow((1 + $rate), -$term)));

	echo '<div id="payment"><label>Monthly Payment:</label> <span style="float: right;">$' . number_format($payment, 2) . '</span></div>';
	
	echo '<table border="1" cellpadding="5" cellspacing="0" width="100%">';
		echo '<tr>';
			echo '<th>#</th>';
			echo '<th>Date</th>';
			echo '<th>Beginning Balance</th>';
			echo '<th>Payment Amt</th>';
			echo '<th>Principal</th>';
			echo '<th>Interest</th>';
			echo '<th>CUM Interest</th>';
			echo '<th>Balance</th>';
		echo '</tr>';
	for($i = 1; $i <= $term; $i++){
		$date = new dateTime($_POST['year'] . '-' . $_POST['month'] . '-' . 1 . " + " . ($i - 1) . " months");
		$beg_balance = ($i == 1 ? $principal : $end_balance); 
		$interest = ($i == 1 ? $principal * $rate : $beg_balance * $rate);
		$cum_int[$i] = ($i == 1 ? $principal * $rate : $beg_balance * $rate);
		$cum_int2 = array_sum($cum_int);
		$principal = $payment - $interest;
		$end_balance = $beg_balance - $principal;
		echo '<tr>';
			echo '<td>' . $i . '</td>';
			echo '<td>' . $date->format("F, Y") . '</td>';
			echo '<td>$' . number_format($beg_balance,2) . '</td>';
			echo '<td>$' . number_format($payment, 2) . '</td>';
			echo '<td>$' . number_format($principal, 2) . '</td>';
			echo '<td>$' . number_format($interest, 2) . '</td>';
			echo '<td>$' . number_format($cum_int2, 2) . '</td>';
			echo '<td>$' . number_format($end_balance, 2) . '</td>';
		echo '</tr>';
	}	
	echo '</table>';
}
?>
</form>
</body>
</html>
