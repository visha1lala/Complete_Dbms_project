<?php
if($_POST['amount'] <= "500000" && $_POST['country'] == 
"India") 
{ echo("Welcome, You are eligible."); } 
else 
{ echo("You're not eligible"); } 
?>