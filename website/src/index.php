

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

    $project_name = $project_domain = $project_issue = $project_due = $project_desc = $project_budget = "";

    //Fetching projects.
    $query_projects = "(select distinct project.name from workon, project 
                            where (manager_id = $user_id OR leader_id = $user_id) AND project.id = workon.project_id )
                        union (select distinct project.name from workon, member, project 
                            where member.member_id = $user_id AND member.team_id = workon.team_id AND project.id = workon.project_id);";
    $result = mysqli_query($mysqli, $query_projects);

    $project_num = mysqli_num_rows($result);
    $project_names = [];

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

	if(array_key_exists('p_button', $_POST)){
        $selected_project_index = $_POST['p_button'];
        $selected_project_name = $project_names[$selected_project_index];

        $query_project_info = "select * from project where name like '$selected_project_name';";
        $result = mysqli_query($mysqli, $query_project_info);

        $row = mysqli_fetch_assoc($result);

        $project_name = $row['name'];
        $project_domain = $row['app_domain'];
        $project_issue = $row['issue_date'];
        $project_due = $row['due_date'];
        $project_desc = $row['description'];
        $project_budget = $row['budget'];

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

        <form action="index.php" method="post" id="projects">
            <?php


            //TODO: Figure out red color issue.
            for($i = 0; $i < $project_num; $i++){
                if(array_key_exists('p_button', $_POST) && $i == $selected_project_index){

                    echo '<button id ="selectedButton" name="p_button" value="'. $i . '" class="projectButton" type="submit" onclick="p_button_click(' . $i .')"><span class="projectSpan">';
                    echo $project_names[$i];
                    echo '</span></button>';
                } else{
                    
                    echo '<button name="p_button" value="'. $i . '" class="projectButton" type="submit" onclick="p_button_click(' . $i .')"><span class="projectSpan">';
                    echo $project_names[$i];
                    echo '</span></button>';
                }

            }
            ?>
        </form>

        <!-- Project Information -->
        <div>
            Name: <?php echo "$project_name"; ?>
            Description: <?php echo "$project_desc"; ?>
            Issue Date: <?php echo "$project_issue"; ?>
            App Domain: <?php echo "$project_domain"; ?>
            Due Date: <?php echo "$project_due"; ?>
            Budget: <?php echo "$project_budget"; ?>
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
