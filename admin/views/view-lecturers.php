<?php
    require("../backend/admin-task-function.php");
    $lecturers = getAllLecturers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../arms.css/admin.css">
    <script src="../custom-jscript/admin.js"></script>
    <title>Document</title>
</head>
<body>
    <div class="menu-bar" onclick="showMenu()">
        <div class="menu-frame">
            <div class="menu-icon-bar-1"></div>
            <div class="menu-icon-bar-2"></div>
            <div class="menu-icon-bar-3"></div>
        </div>
    </div>
    <div id ="view-lecturer-pane">
        <table id = "view-lecturer-table">
            <thead id = "grey-row">
                <th>S/N</th>
                <th>TITLE</th>
                <th>SURNAME</th>
                <th>FIRST NAME</th>
                <th>OTHERNAME</th>
                <th>RANK</th>
                <th>PHONE NUMBER</th>
                <th>EDITOR</th>
            </thead>
            <tbody>
                <?php
                    $s_n = 0;
                    foreach($lecturers as $lecturer){
                        $s_n++;
                        $othername = "";
                        if(array_key_exists("othername",$lecturer)){
                            $othername = $lecturer['othername'];
                        }else{
                            $othername = "";
                        }  
                        if($s_n%2 ==0){
                            echo '<tr id = "grey-row"><td>'.$s_n.'</td><td id = "title_'.$lecturer['id'].'">'.$lecturer['title'].'</td><td id = "surname_'.$lecturer['id'].'" >'.$lecturer['surname'].'</td><td id = "firstname_'.$lecturer['id'].'" > '.$lecturer['firstname'].'</td><td id = "othername_'.$lecturer['id'].'">'.$othername.'</td><td id = "rank_'.$lecturer['id'].'">'.$lecturer['rank'].'</td><td id="mobile_'.$lecturer['id'].'">'.$lecturer['mobile_phone'].'</td><td><button  onclick = "getLecturerEditor('.$lecturer['id'].')">Edit Details</button></td></tr>';
                        }else{
                            echo '<tr><td>'.$s_n.'</td><td id = "title_'.$lecturer['id'].'">'.$lecturer['title'].'</td><td id = "surname_'.$lecturer['id'].'" >'.$lecturer['surname'].'<td id = "firstname_'.$lecturer['id'].'" > '.$lecturer['firstname'].'</td> <td id = "othername_'.$lecturer['id'].'" >'.$othername.'</td><td id = "rank_'.$lecturer['id'].'">'.$lecturer['rank'].'</td><td id="mobile_'.$lecturer['id'].'">'.$lecturer['mobile_phone'].'</td><td><button  onclick = "getLecturerEditor('.$lecturer['id'].')">Edit Details</button></td></tr>';
                        } 
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div class = "course-editor-pane" id = "lecturer-editor">
        <div class = "editor-form" id = "lecturer-editor-form">
            <h3>EDIT LECTURER DETAILS</h3><p id = "editor-close-btn" onclick = "closeLecturerEditor()">X</p>
            <input type="hidden" name="" id = "lectID">
            <input type="hidden" name="" id = "course-num">
            <span><label for="title">Title:</label><input type="text" name="" id="title"></span>
            <span><label for="surname">Surname:</label><input type="text" name="" id="surname"></span>
            <span><label for="firstname">First Name:</label><input type="text" name="" id="firstname"></span>
            <span><label for="othername">Other Name:</label><input type="text" name="" id="othername"></span>
            <span><label for="rank">Rank:</label><input type="text" name="" id="rank"></span>
            <span><label for="mobile_number">Phone Number:</label><input type="text" name="" id="phone-number"></span>
            <span><button class = "update-btn" id ="update-course-btn" onclick = "updateLecturerDetails();">Update</button></span>
            <div id = "edit-lecturer-response-pane">
            
            </div>
        </div>
    </div>
</body>
</html>