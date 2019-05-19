<?php
	include "config.php";
	session_start();

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


    $selected_team_index = -1;

    $board_names = [];
    $board_ids = [];

    function logOut() {
        echo "You are logging out of the system...";
        session_destroy();
        header('Location: form.php');
    }

	//Fetching user information.
	$query_user_info = "SELECT * FROM User WHERE id = $user_id";
	$result = mysqli_query($mysqli, $query_user_info);
	$row = mysqli_fetch_assoc($result);

	$f_name =  $row['first_name'];
	$l_name =  $row['last_name'];



    //Fetching projects.
    $query_projects = "(select project.name, project.id from workon, project 
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

        $query_project_info = "select name, description, app_domain, issue_date, due_date, budget, manager_id, project.id 
                                from project, workon 
                                where project.id = $project_ids[$selected_project_index] and workon.project_id = project.id;";
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
	if(array_key_exists('team_button', $_POST)){
	    $selected_team_index = $_POST['team_button'];
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

	    //TODO: No Boards case
	    else {

        }

    }


?>


<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="index_style.css">
    </head>

    <body>

        <h1>Tasks&Managers</h1>

        <h2>Welcome <?php echo $f_name . " " . $l_name ?> </h2>

        <!-- Projects of the User -->
        <div class="projects">
            <h3>Projects</h3>
            <form action="index.php" method="post" id="projects">
                <?php
                for($i = 0; $i < $project_num; $i++){
                    if($selected_project_index == $i){

                        echo '<button id="selectedButton" name="p_button" value="'. $i . '" class="projectButton" type="submit"><span>';
                        echo $project_names[$i];
                        echo '</span></button>';
                    } else{

                        echo '<button name="p_button" value="'. $i . '" class="projectButton" type="submit"><span>';
                        echo $project_names[$i];
                        echo '</span></button>';
                    }

                }
                ?>
            </form>
        </div>

        <!-- Project Information -->
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
        <div class="teams">
            <h3>Project Teams</h3>

            <?php
                //user is the manager.
                if($user_level == 2){
                    echo '<form method="post">';
                    for($i = 0; $i < $number_of_teams; $i++){

                        if($i == $selected_team_index){
                            echo '<label class="projectButton">';
                            echo '<input type="radio" name="team_button" value="' . $i . '" onclick="this.form.submit()">';
                            echo '<span>' . $project_team_names[$i] . '</span>';
                            echo '</label>';
                        }
                        else {
                            echo '<label class="projectButton">';
                            echo '<input type="radio" name="team_button" value="' . $i . '" onclick="this.form.submit()">';
                            echo '<span>' . $project_team_names[$i] . '</span>';
                            echo '</label>';
                        }

                    }

                    echo '</form>';
                }
                //TODO: other user types screen.
            ?>
        </div>


        <!-- Boards of the team -->
        <div class="boards">
            <h3>Boards</h3>

                <?php
                    for($i = 0; $i < $number_of_boards; $i++){

                        echo '<label>';
                        echo '<input type="radio" name="boards"/>';
                        echo '<span>' . $board_names[$i] . '</span><br>' ;
                        echo '</label>';
                    }
                ?>



        </div>




        <form method="post">
            <input type="submit" name="Logout" id="Logout" value="Logout" /><br/>
        </form>

    </body>
</html>
