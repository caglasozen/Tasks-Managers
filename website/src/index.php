

<?php
	include "config.php";
	session_start();

	function logOut() {
		echo "You are logging out of the system...";
		session_destroy();
		header('Location: form.php');
	}

	$user_id = $_SESSION['user_id'];


	//Fetching user information.
	$query_user_info = "SELECT * FROM User WHERE id = $user_id";
	$result = mysqli_query($mysqli, $query_user_info);
	$row = mysqli_fetch_assoc($result);

	$f_name =  $row['first_name'];
	$l_name =  $row['last_name'];

    //Fetching projects.
    $query_projects = "(select distinct project.name from workon, project 
                            where (manager_id = $user_id OR leader_id = $user_id) AND project.id = workon.project_id )
                        union (select distinct project.name from workon, member, project 
                            where member.member_id = $user_id AND member.team_id = workon.team_id AND project.id = workon.project_id);";
    $result = mysqli_query($mysqli, $query_projects);

    $project_num = mysqli_num_rows($result);
    $project_names = [];
    $selected_project = -1;

    if($project_num > 0){

        while($row = mysqli_fetch_assoc($result)) {
            $project_names[] = $row['name'];
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

	
?>



<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="index_style.css">
        <script>
            var projectNum = "<?php echo $project_num ?>";
            var buttons = [];

            var selected_button;

            function p_button_click(index) {

                var css_buttons = document.querySelectorAll(".projectButton");
                selected_button = index;

                for(var i = 0; i < projectNum; i++){
                    css_buttons[i].style.backgroundColor = "#4286f4";
                }

                css_buttons[index].style.backgroundColor = "#ff0000";
            }
        </script>
    </head>

    <body>

        <h1>Tasks&Managers</h1>

        <h2>Welcome <?php echo $f_name . " " . $l_name ?> </h2>

        <!-- Projects of the User -->
        <h3>Projects</h3>

        <div id="projects">
            <?php

            for($i = 0; $i < $project_num; $i++){
                echo '<button class="projectButton" onclick="p_button_click(' . $i .')"><span class="projectSpan">';
                echo $project_names[$i];
                echo '</span></button>';
            }
            ?>
        </div>

        <!-- Teams of the project -->
        <h3>Project Teams</h3>
        <ul>

        </ul>


        <!-- Boards of the team -->
        <table>
            <tr>
                <th>Team Boards</th>
            </tr>
        </table>




        <form method="post">
            <input type="submit" name="Logout" id="Logout" value="Logout" /><br/>
        </form>

    </body>
</html>
