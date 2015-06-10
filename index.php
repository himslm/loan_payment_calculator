<!DOCTYPE html>
<head>
  <title>Loan Payment Calculator</title>
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
		<td><label>Term (m):</label></td>
		<td><input type="text" name="term" value="<?php echo (isset($_POST['term']) ? $_POST['term'] : NULL); ?>"  size="3" /></td>
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

	echo '<label>Monthly Payment:</label> $' . number_format($payment, 2);
	
	echo '<table border="1" cellpadding="5" cellspacing="0">';
		echo '<tr>';
			echo '<th>#</th>';
			echo '<th>Beginning Balance</th>';
			echo '<th>Payment Amt</th>';
			echo '<th>Principal</th>';
			echo '<th>Interest</th>';
			echo '<th>Balance</th>';
		echo '</tr>';
	for($i = 1; $i <= $term; $i++){	
		$beg_balance = ($i == 1 ? $principal : $end_balance); 
		$interest = ($i == 1 ? $principal * $rate : $beg_balance * $rate);
		$principal = $payment - $interest;
		$end_balance = $beg_balance - $principal;
		echo '<tr>';
			echo '<td>' . $i . '</td>';
			echo '<td>$' . number_format($beg_balance,2) . '</td>';
			echo '<td>$' . number_format($payment, 2) . '</td>';
			echo '<td>$' . number_format($principal, 2) . '</td>';
			echo '<td>$' . number_format($interest, 2) . '</td>';
			echo '<td>$' . number_format($end_balance, 2) . '</td>';
		echo '</tr>';
	}	
	echo '</table>';
}
?>
</form>
</body>
</html>
