$(document).ready(function(){
    $.post("dashboard.php",function(data){
        $("#display-pane").html(data);
        $("#menu-list").hide();
        checkTotalAdmin();
    })
    $("#selected-student-form-level1").hide();
});

function hideMenu(){
    $("#menu-list").hide(500);
    $(".menu-frame").show();
    $("#dashboard-item-pane").show();
}

function showMenu(){
    $("#menu-list").show(500);
    $(".menu-frame").hide();
    $("#dashboard-item-pane").show();
    checkTotalAdmin();
}

function showDashboard(){
    $.post("dashboard.php",function(data){
        $("#display-pane").html(data);
        $("#dashboard-item-pane-alter").hide();
        hideMenu();
    });
}

function uploadCourseView(){
    $.post("upload-courses.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
    });
}

function registerStudents(){
    $.post("register-students.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
    });
}

function viewStudents(){
    $.post("view-students.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
    });
}

function viewLecturers(){
    $.post("view-lecturers.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
        $("#lecturer-editor").hide();
    });
}

function viewCourses(){
    $.post("view-courses.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
        $("#course-editor-pane").hide(); 
    });
}

function getEditorPane(a){
    $("#course-editor-pane").show();
    var courseID = $("#course-num_"+a+"").html();
    var code = $("#code_"+a+"").html();
    var title = $("#title_"+a+"").html();
    var units = $("#units_"+a+"").html();
    var level = $("#level_"+a+"").html();
    var semester = $("#semester_"+a+"").html();
    var takenby = $("#takenby_"+a+"").html();
    var status = $("#status_"+a+"").html();

    $("#course-num").val(courseID);
    $("#course-code").val(code);
    $("#course-title").val(title);
    $("#course-units").val(units);
    $("#course-level-taken").val(level);
    $("#course-semester").val(semester);
    $("#course-takenby").val(takenby);
    $("#course-status").val(status);
    
}

function updateCourseDetails(){
    var courseID = $("#course-num").val();
    var code = $("#course-code").val();
    var title = $("#course-title").val();
    var units = $("#course-units").val();
    var level = $("#course-level-taken").val();
    var semester = $("#course-semester").val();
    var takenby = $("#course-takenby").val();
    var status = $("#course-status").val();
    $.post("../backend/update-course-details.php",{courseID:courseID, code:code, title:title, units:units, level:level, semester:semester, takenby:takenby, status:status}, function(data){
        if(data == "success"){
            $("#edit-course-response-pane").html('<p style="color:green;">Update successful.</p><br><button onclick = "closeEditor()">OK</button>');

        }else{
            $("#edit-course-response-pane").html('<p style="color:red;">'+data+'</p>');
        }
    })
    
}

function closeEditor(){
    $("#course-editor-pane").hide();
    viewCourses();
}

function closeLecturerEditor(){
    $("#lecturer-editor").hide();
    viewLecturers();
}

function viewActiveStudents(){
    $.post("view-active-students.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
    });
}

function viewSuspendedStudents(){
    $.post("view-deffered-students.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
    });
}

function viewAttendance(){
    $.post("view-attendance.php",function(data){
        $("#display-pane").html(data);
        $("#select_att_option").hide();
        $("#course-table-display-pane").hide();
        $("#att-sheet").hide();
        hideMenu();
    });
}

function uploadResult(){
    $.post("result-upload.php",function(data){
        $("#display-pane").html(data);
        $("#result-batch-upload").hide();
        $("#result-form-upload").hide();
        hideMenu();
    });
}

function editResultView(){
    $.post("edit-student-result.php", function(data){
        $("#display-pane").html(data);
        hideMenu();
    })
}

function viewGeneralResult(){
    $.post("general-result.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
    });
}

function viewIndividualResult(){
    $.post("individual-result.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
    });
}

function registerLecturers(){
    $.post("register-lecturers.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
    });
}

function checkTotalAdmin(){
    $.post("../backend/check-total-admin.php",function(data){
        if(data==0 || data==1){
            $("#add-second-admin").show();
        }else{
            $("#add-second-admin").hide();
        }
        
    });
}
function addSecondAdmin(){
    var type = $("#second_admin").val();
    if( type == "fresh_lecturer"){
        $.post("add-second-admin.php",function(data){
            $("#display-pane").html(data);
            hideMenu();
        });
    }else if(type == "existing_lecturer"){
        $.post("add-admin-from-system-view.php",function(data){
            $("#display-pane").html(data);
            hideMenu();
        });
    }
}

function courseAllocation(){
    $.post("course-allocation.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
    });
}

function updateSession(){
    
}
function LoadTranscriptView(){
    $.post("transcript-main-view.php",function(data){
        $("#display-pane").html(data);
        hideMenu();
    });
}

function logout(){
    $.post("../backend/logout.php", function(data){
        if(data=="Session destroyed"){
            window.location.href = "../preaccess/login-view.php";
        }
    })
}