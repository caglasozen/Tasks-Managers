<?php
	include "config.php";
	session_start();
	
	function logOut() {
		echo "You are logging out of the system...";
		session_destroy();
		header('Location: form.php');
	}
	
	function goBack() {
		echo "You are going back";
		header('Location: index.php');
	}
	
	$user_id = $_SESSION['user_id'];
	$proj_id = $_SESSION['project_id'];
	$board_id = $_SESSION['board_id'];
	
	/*
	//Fetching project information.
	$query_proj_info = "SELECT * FROM Project WHERE id = 1";
	$result_pr = mysqli_query($mysqli, $query_proj_info);
	$row_pr = mysqli_fetch_assoc($result_pr);
	
	//Fetching board information.
	$query_board_info = "SELECT * FROM Board WHERE id = $board_id";
	$result_bo = mysqli_query($mysqli, $query_board_info);
	$row_bo = mysqli_fetch_assoc($result_bo);
	
	//Fetching list information.
	$query_list_info = "SELECT * FROM List WHERE board_ID = $board_id";
	$result_li = mysqli_query($mysqli, $query_list_info);
	$row_li = mysqli_fetch_assoc($result_li);
	 
	
	if(!isset($user_id)){
		header('Location: form.php');
	}*/
	

	
	if(array_key_exists('Logout',$_POST)){
		logOut();
	}
	
	if(array_key_exists('GoBack',$_POST)){
		goBack();
	}
	
?>



<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="board_style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script>
function newCard() {
	document.getElementById("cardForm").style.display = "block";
}

function closeNewCard() {
	document.getElementById("cardForm").style.display = "none";
}

function newList() {
	document.getElementById("ListForm").style.display = "block";
}

function closeNewList() {
	document.getElementById("ListForm").style.display = "none";
}


</script>


</head>
<body>


<div class="boardBox">

<h3> Board 1 </h3>

<div class="cornBut">

<button onclick="newList()">New List</button> <center>

</div>

	<div class="column">
<div class="cornBut">

<button class="newcardBut" onclick="newCard()">+</button>

</div>

	<h3>List 1  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>
		<div class="card">
		<h3>Card 1  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>
		<p>Some text</p>
		<p>Some text</p>
		</div>

		<div class="card">
		<h3>Card 2  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>
		<p>Some text</p>
		<p>Some text</p>
		</div>

	</div>

	<div class="column">
		<div class="cornBut">

		<button class="newcardBut" onclick="newCard()">+</button>

		</div>
		<h3>List 2  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>
		<div class="card">
		<h3>Card 1  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>
		<p>Some text</p>
		<p>Some text</p>
		</div>

		<div class="card">
		<h3>Card 2  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>
		<p>Some text</p>
		<p>Some text</p>
		</div>

	</div>

	<div class="column">
<div class="cornBut">

<button class="newcardBut" onclick="newCard()">+</button>

</div>
	<h3>List 3  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>
	<div class="card">
	<h3>Card 1  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>
	<p>Some text</p>
	<p>Some text</p>
	</div>

	<div class="card">
	<h3>Card 2  <a class="btn btn-sm btn-danger" href="#">X</a> </h3>
	<p>Some text</p>
	<p>Some text</p>
	</div>
	</div>

</div>

<div class="form-popup" id="cardForm">
<form action="/board_manager.php" class="form-container">
<h1>Create New Card</h1>

<label for="email"><b>Email</b></label>
<input type="text" placeholder="Enter Email" name="email" required>

<label for="psw"><b>Password</b></label>
<input type="password" placeholder="Enter Password" name="psw" required>

<button type="submit" class="but">Login</button>
<button type="button" class="but_cancel"  onclick="closeNewCard()">Close</button>
</form>
</div>

<div class="form-popup" id="ListForm">
<form action="/board_manager.php" class="form-container" >
<h1>Create New List</h1>

<label for="listName"><b>List Name</b></label>
<input type="text" placeholder="ListName" name="listName" required>

<label for="listDesc"><b>Description</b></label>
<input type="text" placeholder="Enter List Description" name="listDesc">

<label for="listDue"><b>Due Date</b></label>
<input type="date" name="listDue" required>


<button type="submit" name="submitList" id="submitList" class="but">Login</input>
<button type="button" class="but_cancel"  onclick="closeNewList()">Close</button>
</form>
</div>

<div class="logoutButton">

<form  align="right" method="post">
<input type="submit" name="Logout" id="Logout" value="Log Out" /><br/>
</form>

</div>

<div class="gobackButton">

<form  align="right" method="post">
<input type="submit" name="GoBack" id="GoBack" value="Go Back"  /><br/>
</form>

</div>


</body>
</html>
