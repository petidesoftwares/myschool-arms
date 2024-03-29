<?php
    require("../backend/admin-task-function.php");
    $titles = getTitles();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../custom-css/base-customstyle.css"> -->
    <link rel="stylesheet" href="../arms.css/arms-1.css">
    <script src="../custom-jscript/jquery-3.5.1.min.js"></script>
    <script src="../custom-jscript/admin.js"></script>
    <title>Add Admin</title>
</head>
<body>
    <div class="col-12">
        <div class="menu-bar" onclick="showMenu()">
                <div class="menu-frame">
                    <div class="menu-icon-bar-1"></div>
                    <div class="menu-icon-bar-2"></div>
                    <div class="menu-icon-bar-3"></div>
                </div>
        </div>
        <!-- <div class="col-10"></div> -->
        <div>
            <!-- <div class="col-3"></div> -->
            <div id="second-admin-form-pane">
                <form id="second_admin_form">
                    <span>REGISTER SECOND ADMIN</span><br>
                    <label for="title">Title:</label><select name="title" id="title">
                        <option>Select Title</option>
                        <?php
                        foreach($titles as $title){
                            echo '<option value="'.$title['title'].'">'.$title['title'].'';
                        }
                        ?>
                    </select><br>
                    <input type="text" name="fname" id="fname" placeholder="Enter first name">
                    <input type="text" name="surname" id="surname" placeholder="Enter surname">
                    <input type="text" name="othername" id="othername" placeholder="Enter other name"><br>
                    <label>Gender:</label> 
                    <input type="radio" name="gender" value="Male" id="male" checked> <label for="male">Male</label>
                    <input type="radio" name="gender" value="Female" id="female"> <label for="female"></label>Female</label><br>
                    <label for="title">Rank:</label><select name="rank" id="rank">
                        <option>Select Rank</option>
                        <option value="Professor">Professor</option>
                        <option value="Reader">Reader</option>
                        <option value="Senior Lecturer">Senior Lecturer</option>
                        <option value="Lecturer l">Lecturer l</option>
                        <option value="Lecturer ll">Lecturer ll</option>
                        <option value="Assistant Lecturer">Assistant Lecturer</option>
                    </select><br>
                    <input type="tel" name="mobile" id="mobile" placeholder="Enter phone number">
                    <input type="email" name="email" id="email" placeholder="Enter email address"><br>
                    <label>Position:</label> 
                    <input type="radio" name="position" value="EO" id="eo" checked> <label for="eo">Exam Officer</label>
                    <input type="radio" name="position" value="HOD" id="hod"> <label for="hod"></label>Head Of Department</label><br>
                    <input type="password" name="password" id="password" placeholder="Enter Password">
                    <input type="password" name="c_password" id="c_password" placeholder="Confirm Password"><br>
                    <input type="submit" value="Submit" id="submit" onclick = "addSecondAdminBtn()">
                </form>
                
            </div>
        </div>
    </div>
    <div class="col-12-custom" id = 'modal'>
        <!-- <input type = 'button' value = 'X' onclick = 'hideModal()' id = 'closeModal' /> -->
        <div class="col-3"></div>
        <div class="col-5" id="validation-info-board">
            <h3>Message</h3>
            <div id="validation-info"></div>
            <button id ="clear-modal" onclick = 'hideModal()'>OK</button>
        </div>
        <div class="col-3"></div>
    </div>
    
</body>
</html>