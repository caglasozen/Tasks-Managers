<?php
	if (!isset($_SESSION)){
		session_start();
	}
	
	include "config.php";

	$user_id = $_SESSION['user_id'];
    $project_domain = $project_issue = $project_due = $project_desc = $project_budget = "";
    $project_position = "";
    $project_manager_id = "";


    $user_level = -1; // -1 for undefined, 0 for standard, 1 for leader, 2 for manager

    $projects = [];
    $project_names = []; //same index of the $project_ids array is the id of this project.
    $project_ids = [];

    $selected_project_index = $_SESSION['project_index'];
    $selected_project_id = -1;
    $selected_project_name = "";

    $project_team_names = [];
    $project_team_ids = [];
    $selected_team_name = "";
    $selected_team_id = -1;


    $selected_team_index = $_SESSION['team_index'];

    $board_names = [];
    $board_ids = [];

    function logOut() {
        session_destroy();
        header('Location: form.php');
    }

    function createBoard($db, $board_name, $board_desc, $board_issue, $board_due, $team_id){
        $query_create_board = "insert into board (name, description, issue_date, due_date, team_id) 
                                values ('$board_name', '$board_desc', '$board_issue', '$board_due', $team_id);";

        if (mysqli_query($db, $query_create_board)) {
            header("Refresh:0");
        } else {
            echo "Error: " . $query_create_board . "<br>" . mysqli_error($db);
        }

    }

    function deleteBoard($db){
        $board_id = $_POST['board_id'];
        $query_delete_board = "delete from board where id= $board_id;";

        if (mysqli_query($db, $query_delete_board)) {
            header("Refresh:0");
        } else {
            echo "Error: " . $query_delete_board . "<br>" . mysqli_error($db);
        }
    }

    function createProject($db, $project_name, $project_desc,
                           $project_issue, $project_app,
                           $project_due, $project_budget){

        global $user_id;

        $query_create_project = "insert into project (name, description, issue_date, app_domain, due_date, budget) 
                                  values ('$project_name', '$project_desc', '$project_issue', '$project_app', '$project_due', '$project_budget')";

        mysqli_query($db, $query_create_project);

        $query_get_last_insert = "select LAST_INSERT_ID();";

        $result = mysqli_query($db, $query_get_last_insert);
        $row = mysqli_fetch_assoc($result);
        $project_id = $row['LAST_INSERT_ID()'];

        $query_create_team = "insert into team values ();";
        mysqli_query($db, $query_create_team);

        $result = mysqli_query($db, $query_get_last_insert);
        $row = mysqli_fetch_assoc($result);
        $team_id = $row['LAST_INSERT_ID()'];

        $query_check_manager = "select count (*) from manager where id = $user_id;";
        $result = mysqli_query($db, $query_check_manager);
        $row = mysqli_fetch_assoc($result);
        if($row['count(*)'] < 1){
            $query_create_manager = "insert into manager values ($user_id);";
            mysqli_query($db, $query_create_manager);
        }

        $query_add_workon = "insert into workon (team_id, project_id, manager_id) values ('$team_id', '$project_id', '$user_id');";
        mysqli_query($db, $query_add_workon);

        header("Refresh:0");

    }

    function addUser($db, $mail, $role, $team_id){
        $query_new_user_id = "select id from user where email = '$mail';";
        $result = mysqli_query($db, $query_new_user_id);

        if(mysqli_num_rows($result) < 1){
            echo '<script>alert("User cannot found!");</script>>';
        }
        else {

            $row = mysqli_fetch_assoc($result);
            $new_user_id = $row['id'];

            $query_check_standard = "select count (*) from standarduser where id= '$new_user_id';";


            $result = mysqli_query($db, $query_check_standard);
            $row = mysqli_fetch_assoc($result);

            if($row['count(*)'] < 1){
                $query_create_standard = "insert into standarduser values ($new_user_id);";
                mysqli_query($db, $query_create_standard);

                $query_create_worker = "insert into worker (id) values ($new_user_id);";
                mysqli_query($db, $query_create_worker);
            }

            $query_insert_member = "insert into member () values ($new_user_id, '$role', $team_id);";
            mysqli_query($db, $query_insert_member);

        }

    }

    function deleteUser($db, $mail, $team_id){
        $query_user_id = "select id from user where email = '$mail';";
        $result = mysqli_query($db, $query_user_id);
        if(mysqli_num_rows($result) < 1){
            echo '<script>alert("User cannot found!");</script>>';
        }
        else {
            $row = mysqli_fetch_assoc($result);
            $user_id = $row['id'];

            $query_delete_user = "delete from member where member_id='$user_id' and team_id = '$team_id';";
            mysqli_query($db, $query_delete_user);

        }
    }

	//Fetching user information.
	$query_user_info = "SELECT * FROM user WHERE id = $user_id";
	$result = mysqli_query($mysqli, $query_user_info);
	$row = mysqli_fetch_assoc($result);

	$f_name =  $row['first_name'];
	$l_name =  $row['last_name'];



    //Fetching projects.
    $query_projects = "(select project.name,project.id from workon, project 
                        where (manager_id = $user_id OR leader_id = $user_id) AND project.id = workon.project_id ) 
                        union (select distinct project.name, project.id from workon, member, project 
                        where member.member_id = $user_id AND member.team_id = workon.team_id AND project.id = workon.project_id);";
    $result = mysqli_query($mysqli, $query_projects);
    $project_num = mysqli_num_rows($result);

    if($project_num > 0){

        while($row = mysqli_fetch_assoc($result)) {
            $project_names[] = $row['name'];
            $project_ids[] = $row['id'];
        }



    }

    //TODO initial screen with 0 projects.
    else {

    }

	if(!isset($user_id)){
		header('Location: form.php');
	}

	if(array_key_exists('Logout',$_POST)){
		logOut();
	}

	//Project selected.
	if(array_key_exists('p_button', $_POST) || $selected_project_index > -1){

	    if(array_key_exists('p_button', $_POST)){
            $selected_project_index = $_POST['p_button'];
        }else {
	        $selected_project_index = $_SESSION['project_index'];
        }

        $selected_project_name = $project_names[$selected_project_index];
        $selected_project_id = $project_ids[$selected_project_index];
        $_SESSION['project_index'] = $selected_project_index;

        $query_project_info = "select * from project, workon where project.ID = $project_ids[$selected_project_index] and workon.project_ID = project.ID;";
        $result = mysqli_query($mysqli, $query_project_info);

        $row = mysqli_fetch_assoc($result);

        $project_name = $row['name'];
        $project_domain = $row['app_domain'];
        $project_issue = $row['issue_date'];
        $project_due = $row['due_date'];
        $project_desc = $row['description'];
        $project_budget = $row['budget'];
        $project_manager_id = $row['manager_id'];

        if($project_manager_id == $user_id){
            $user_level = 2;
        }

        //Fetching team names
        $query_teams = "select team.name, team.id from project, workon, team where workon.project_id = project.id and project_id = $selected_project_id and workon.team_id = team.id;";
        $result = mysqli_query($mysqli, $query_teams);
        $number_of_teams = mysqli_num_rows($result);
        if($number_of_teams > 0){
            while($row = mysqli_fetch_assoc($result)) {
                $project_team_names[] = $row['name'];
                $project_team_ids[] = $row['id'];
            }
        }
        //TODO: WITHOUT ANY TEAMS SCREEN.
        else{

        }
    }


	//Team is selected.
	if(array_key_exists('team_button', $_POST)|| $selected_team_index > -1){

        if(array_key_exists('team_button', $_POST)){
            $selected_team_index = $_POST['team_button'];
            $_SESSION['team_index'] = $selected_team_index;
        }else {
            $selected_team_index = $_SESSION['team_index'];
        }


	    $selected_team_id = $project_team_ids[$selected_team_index];
	    $selected_team_name = $project_team_names[$selected_team_index];


	    //fetching team boards
	    $query_team_boards = "select id, name from board where team_id = $selected_team_id;";
	    $result = mysqli_query($mysqli, $query_team_boards);

	    $number_of_boards = mysqli_num_rows($result);

	    if($number_of_boards > 0){
            while($row = mysqli_fetch_assoc($result)){
                $board_ids[] = $row['id'];
                $board_names[] = $row['name'];
            }
        }

	    $query_team_leader = "select leader_id from workon where team_id = $selected_team_id;";
	    $result = mysqli_query($mysqli, $query_team_leader);

	    if(mysqli_num_rows($result) > 0){
	        $row = mysqli_fetch_assoc($result);
	        if($user_id == $row['leader_id'] && $user_level !=2){
	            $user_level = 1;
            }
	        else if($user_level != 2){
	            $user_level = 0;
            }

        }


	    //TODO: No Boards case
	    else {

        }

    }


	if (isset($_POST['account'])) {
        header('Location: account.php');
    }


	if(array_key_exists('create_board_button', $_POST)) {
        createBoard($mysqli, $_POST['board_name'], $_POST['board_desc'], date('Y-m-d'), $_POST['board_due'], $selected_team_id);

    }

    //delete board
	if(array_key_exists('board_delete', $_POST) ){
        deleteBoard($mysqli);
	}
    if(array_key_exists('create_project_button', $_POST) ){

        createProject($mysqli, $_POST['project_name'], $_POST['project_desc'],
                    date('Y-m-d'), $_POST['project_app_domain'], $_POST['project_due'],
                    $_POST['project_budget']);
    }

    if(array_key_exists('add_user_button', $_POST) ){
        addUser($mysqli, $_POST['manage_email'], $_POST['manage_role'], $selected_team_id);
    }

    if(array_key_exists('delete_user_button', $_POST) ){
        deleteUser($mysqli, $_POST['delete_mail'], $selected_team_id);
    }



?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="index_style.css">
    </head>

    <body>
        <div class="row">

            <h1>Tasks&Managers</h1>
            <div class="logoutButton">
                <form method="post">
                    <input  type="submit" name="Logout" id="Logout" value="Logout" />
                </form>
            </div>


            <div class="accountButton">
                <form method="post" >
                    <input  type="submit" name="account" value="Account" />
                </form>
            </div>
        </div>

        <div class="row"></div>

        <h2>Welcome <?php echo $f_name . " " . $l_name ?> </h2>
        <h2>Title:
            <?php
            if($user_level == 0){
                echo 'Standard User';
            }
            else if($user_level == 1){
                echo 'Team Leader';
            }
            else if($user_level == 2){
                echo 'Manager';
            }
            ?>
        </h2>



        <h2>Projects</h2>

<div >

        <!-- Projects of the User -->
        <div class="container">

            <form action="index.php" method="post">
                <?php
                for($i = 0; $i < $project_num; $i++){
                    if($selected_project_index == $i){

                        echo '<button id="selectedButton" class = "projectButton" name="p_button" value="'. $i . '"  type="submit"><span>';
                        echo $project_names[$i];
                        echo '</span></button>';
                    } else{

                        echo '<button name="p_button" value="'. $i . '" class="projectButton" type="submit"><span>';
                        echo $project_names[$i];
                        echo '</span></button>';
                    }

                }
                ?>
                    <div>
                    Name: <?php echo "$selected_project_name"; ?> <br>
                    Description: <?php echo "$project_desc"; ?> <br>
                    Issue Date: <?php echo "$project_issue"; ?> <br>
                    App Domain: <?php echo "$project_domain"; ?> <br>
                    Due Date: <?php echo "$project_due"; ?> <br>
                    Budget: <?php echo "$project_budget"; ?> <br>
                    Manager: <?php echo "$project_manager_id"?> <br>
                    </div>


                <!-- Teams of the project -->
                    <div >
                        <h3>Teams</h3>

                        <?php
                            //user is the manager.
                            echo '<form method="post">';
                            for($i = 0; $i < $number_of_teams; $i++){

                                if($i == $selected_team_index){
                                    echo '<button id="selectedButton" name="team_button" value="'. $i . '" class="projectButton" type="submit">';
                                    echo '<span>' . $project_team_names[$i] . '</span>';
                                }
                                else {
                                    echo '<button name="team_button" value="'. $i . '" class="projectButton" type="submit">';
                                    echo '<span>' . $project_team_names[$i] . '</span>';
                                }
                            }
                            echo '</form>';

                            //TODO: other user types screen.
                        ?>
                    </div>


                    <!-- Boards of the team -->
                    <div>
                        <h3>Boards</h3>

            <?php
                if($number_of_boards > 0){

                    if($user_level == 2){
                        echo '<form action="board_manager.php" method="post">';
                    }
                    else if ($user_level == 1){
                        echo '<form action="board_leader.php" method="post">';
                    }
                    else {
                        echo '<form action="board_user.php" method="post">';
                    }

                    for($i = 0; $i < $number_of_boards; $i++){

                        echo '<input type="radio" name="board_id" value="' . $board_ids[$i] .'"/>';
                        echo '<span>' . $board_names[$i] . '</span><br>' ;
                    }

                    echo '<input type="submit" name="homepage_setup" value="Go to the board!"/>';

                    if($user_level == 1){
                    echo '<button type="submit" name="board_delete" formaction="index.php">Delete Board</button>';
                    }

                    echo '<input type="hidden" name="project_id" value="' . $selected_project_id . '" />';
                    echo '<input type="hidden" name="user_id" value="' . $user_id . '" />';
                    echo '</form>';
                }



                if($user_level == 1){
                    //create board pop-up.
                    echo '<button class="open-button" onclick="openCreateForm()">Create Board</button>';

                    echo '<div class="create_board" id="create_board">';
                        echo '<form action="index.php" class="form-container" method="post">';
                        echo '<h1>Create Board</h1>';

                        echo '<label for="board_name"><b>Board Name</b></label>';
                        echo '<input type="text" placeholder="Enter Board Name" name="board_name" required>';

                        echo '<label for="board_desc"><b>Description</b></label>';
                        echo '<input type="text" placeholder="Enter Description" name="board_desc" required>';

                        echo '<label for="board_due"><b>Due Date</b></label>';
                        echo '<input type="date" name="board_due" required>';

                        echo '<button type="submit" name="create_board_button" class="btn">Create Board</button>';
                        echo '<button type="button" class="btn cancel" onclick="closeCreateForm()">Close</button>';
                        echo '</form>';
                    echo '</div>';

                }

            ?>
        </div>




                    <button class="open-create-project" onclick="openCreateProject()">Create Project</button>

                    <div class="create_project" id="create_project">
                        <form action="index.php" class="form-container" method="post">
                            <h1>Create Project</h1>

                            <label for="project_name"><b>Project Name</b></label>
                            <input type="text" placeholder="Enter Project Name" name="project_name" required>

                            <label for="project_desc"><b>Description</b></label>
                            <input type="text" placeholder="Enter Description" name="project_desc" required>

                            <label for="project_budget"><b>Budget</b></label>
                            <input type="number" placeholder="Enter Budget" name="project_budget"><br>

                            <label for="project_app_domain"><b>Application Domain</b></label>
                            <input type="text" placeholder="Enter Application Domain" name="project_app_domain">

                            <label for="project_due"><b>Due Date</b></label>
                            <input type="date" name="project_due" required>

                            <button type="submit" name="create_project_button" class="btn">Create Project</button>
                            <button type="button" class="btn cancel" onclick="closeCreateProject()">Close</button>
                            </form>
                    </div>



                 <!-- Project Information -->


            </form>
        </div>

</div>



        <script>
            function openCreateForm() {
                document.getElementById("create_board").style.display = "block";
            }

            function closeCreateForm() {
                document.getElementById("create_board").style.display = "none";
            }

            function openCreateProject() {
                document.getElementById("create_project").style.display = "block";
            }

            function closeCreateProject() {
                document.getElementById("create_project").style.display = "none";
            }
        </script>


        <!-- Manage Teams -->

        <!-- Trigger/Open The Modal -->
        <?php
            if($user_level == 2 && $selected_team_id > -1){
                echo '<button name="modal_button" id="myBtn" class="projectButton">Add User to Team</button>';
                echo '<button name="delete_modal_button" id="delete_modal_button" class="projectButton">Remove User From Team</button>';
            }
        ?>

        <!-- Insert Modal -->
        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close">&times;</span>
                    <h2>Add User to Team</h2>
                </div>
                <div class="modal-body">
                    <form action="index.php" class="form-container" method="post">
                        <label for="manage_email"><b>User Email</b></label>
                        <input type="text" placeholder="Enter Email" name="manage_email">

                        <label for="manage_role"><b>User Role</b></label>
                        <input type="text" placeholder="Enter Role" name="manage_role">

                        <button type="submit" name="add_user_button" class="btn">Add User</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <h3>Add User to Team</h3>
                </div>
            </div>

        </div>



        <!-- Delete Modal -->
        <div id="deleteModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" id="delSpan">&times;</span>
                    <h2>Remove User from Team</h2>
                </div>
                <div class="modal-body">
                    <form action="index.php" class="form-container" method="post">
                        <label for="delete_mail"><b>User Email</b></label>
                        <input type="text" placeholder="Enter Email" name="delete_mail">


                        <button type="submit" name="delete_user_button" class="btn">Remove User</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <h3>Remove User from Team</h3>
                </div>
            </div>

        </div>

        <script>
            // Get the modal
            var modal = document.getElementById("myModal");
            var deleteModal = document.getElementById("deleteModal");

            // Get the button that opens the modal
            var btn = document.getElementById("myBtn");
            var delete_btn = document.getElementById("delete_modal_button");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];
            var delete_span = document.getElementById("delSpan");

            // When the user clicks the button, open the modal
            btn.onclick = function() {
                modal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks the button, open the modal
            delete_btn.onclick = function() {
                deleteModal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            delete_span.onclick = function() {
                deleteModal.style.display = "none";
            }

        </script>


    </body>
</html>
