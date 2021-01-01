<?php
    function getTitles(){
        require('db_conn.php');
        if($conn){
            $getTitles =mysqli_query($conn, "SELECT title FROM title")or die(mysqli_error($conn));
            if(mysqli_num_rows($getTitles)>0){
                return $getTitles;
            }
        }
    }

    function getSessions(){
        require('db_conn.php');
        if($conn){
            $querySessions = mysqli_query($conn, "SELECT year FROM academic_session ORDER BY year DESC") or die(mysqli_error($conn));
            if(mysqli_num_rows($querySessions)>0){
                return $querySessions;
            }
        }
    }

    function getCurrentSession(){
        require('db_conn.php');
        if($conn){
            $querySessions = mysqli_query($conn, "SELECT year FROM academic_session ORDER BY year DESC LIMIT 1") or die(mysqli_error($conn));
            if(mysqli_num_rows($querySessions)>0){
                return $querySessions;
            }
        }
    }


    function getAllOptions(){
        require('db_conn.php');
        if($conn){
            $getOptions = mysqli_query($conn,"SELECT * FROM `option`") or die(mysqli_error($conn));
            if(mysqli_num_rows($getOptions)>0){
                return $getOptions;
            }
        }
    }

    function getAllStudents(){
        require('db_conn.php');
        if($conn){
            $queryAllStudents = mysqli_query($conn, "SELECT student.matno, student.surname, student.firstname, (SELECT student_othernames.othername FROM student_othernames WHERE student_othernames.student_id=student.id) AS othername    FROM student, student_othernames") or die(mysqli_error($conn));
            if(mysqli_num_rows($queryAllStudents)>0){
                return $queryAllStudents;
            }
        }
    }

    function getAdminMatchedStatus(){
        require('db_conn.php');
        if($conn){
            $getAdminLecturer = mysqli_query($conn, "SELECT id, title, lecturer.firstname, lecturer.surname, (SELECT lecturer_othername.othername FROM lecturer_othername WHERE lecturer.id = lecturer_othername.lecturer_id) AS othername FROM lecturer, lecturer_othername") or die(mysqli_error($conn));
            if(mysqli_num_rows($getAdminLecturer)>0){
                return $getAdminLecturer;
            }
        }
    }

    function getAttebdanceOptions(){
        require('db_conn.php');
        if($conn){
            $getOptions = mysqli_query($conn, "SELECT option FROM option WHERE option !='All'") or die(mysqli_error($conn));
            if(mysqli_num_rows($getOptions)>0){
                return $getOptions;
            }
        }
    }

    function getAttendance($code, $session){
        require('db_conn.php');
        if($conn){
            $getEnrolledLevel = mysqli_query($conn,"SELECT level FROM course_registration WHERE code = '".$code."' AND session = ".$session."") or die(mysqli_error($conn));
            if(mysqli_num_rows($getEnrolledLevel)>0){
                $attArray = array();
                while($rows=mysqli_fetch_assoc($getEnrolledLevel)){
                    $levelarray = array();
                    $getMatno = mysqli_query($conn, "SELECT matno FROM `course_registration` WHERE session=".$session." AND level=".$rows['level']." AND code='".$code."' AND score=-1 ") or die(mysqli_error($conn));
                    if(mysqli_num_rows($getMatno)>0){
                        while($rows = mysqli_fetch_assoc($getMatno)){
                            $queryList = mysqli_query($conn, "SELECT matno, firstname, surname, (SELECT othername FROM student_othernames WHERE student.id = student_othernames.student_id) as othername FROM student WHERE matno = '".$rows['matno']."'") or die(mysqli_error($conn));
                            while($bioData = mysqli_fetch_assoc($queryList)){
                                $levelarray[]=$bioData;
                            }
                        }
                    }
                    $attArray[] = $levelarray;
                }
                return $attArray;
            }
        }
    }

    /**************** General Result Computation Starts Here *******************/

    function getStudentGrade($score){
        $studentGrade;
        if($score>=70){
            $studentGrade='A';
        }elseif($score>=60 && $score<=69){
            $studentGrade='B';
        }elseif($score>=50 && $score<=59){
            $studentGrade='C';
        }elseif($score>=45 && $score<=49){
            $studentGrade='D';
        }elseif($score>=40 && $score<=44){
            $studentGrade='E';
        }else{
            $studentGrade='F';
        }
        return $studentGrade;
    }

    function getCourseGrade($studentGrade){
        $courseGrade =0;
        if($studentGrade=='A'){
            $courseGrade=5;
        }elseif($studentGrade=='B'){
            $courseGrade=4;
        }elseif($studentGrade=='C'){
            $courseGrade=3;
        }elseif($studentGrade=='D'){
            $courseGrade=2;
        }elseif($studentGrade=='E'){
            $courseGrade=1;
        }else{
            $courseGrade=0;
        }
        return $courseGrade;
    }
    function getGradePoint($unit,$courseGrade){
        return $unit*$courseGrade;
    }
    function getCumGradePoint($gradePoint){
        $cumGradePoint=0;
        $cumGradePoint+=$gradePoint;
        return $cumGradePoint;
    }

    function getIndividualResultRemarks($score){
        $remark = "";
        if($score>45){
            $remark="PASS";
        }else{
            $remark = "FAIL";
        }
        return $remark;
    }


    function generateResultAppendix($session, $level, $semester_in){
        require('db_conn.php');
        if($conn){
            $resultArray=array();
            $semester ="";
            $courseCodes;
            $repeatedCourses;
            $listOfStudent;
            $newCourses;
            $courseBasedResult;

            $cgp = 0;
            $cumUnits = 0;
            $cgpa=0;

            if($semester_in=="First Semester"){
                $semester = 'First';
            }else{
                $semester='Second';
            }
        
            $getCourseCodes = mysqli_query($conn,"SELECT DISTINCT course_registration.code FROM course_registration, course WHERE course_registration.code=course.code AND course_registration.level=".$level." AND course.semester='".$semester_in."'") or die(mysqli_error($conn));
            if(mysqli_num_rows($getCourseCodes)>0){
                $courseCodes = $getCourseCodes;
            }
            $queryNewCourses = mysqli_query($conn,"SELECT DISTINCT course_registration.code, course.units FROM course, course_registration WHERE course.code = course_registration.code AND course_registration.level= course.level_taken AND course.semester='".$semester_in."' AND course_registration.level=".$level." AND course_registration.session=".$session."") or die(mysqi_error($conn));
            if(mysqli_num_rows($queryNewCourses)>0){
                $newCourses = $queryNewCourses;
            }
            $queryRepeatedCourse = mysqli_query($conn,"SELECT DISTINCT course_registration.code, course.units FROM course_registration, course, student WHERE course_registration.code= course.code AND student.matno = course_registration.matno AND
                                    course.level_taken<course_registration.level AND course.semester='".$semester_in."' AND course_registration.level=".$level." AND course_registration.session=".$session."") or die(mysqli_error($conn));
            if(mysqli_num_rows($queryRepeatedCourse)>0){
                $repeatedCourses = $queryRepeatedCourse;
            }
            if($level==100){
                $queryListOfStudents = mysqli_query($conn,"SELECT DISTINCT matno FROM course_registration, course WHERE course_registration.code=course.code AND course.semester=? AND level =".$level." AND course_registration.session=".$session."")or die(mysqli_error($conn));
                // $listOfStudent = $queryListOfStudents;
                while($student_id = mysqli_fetch_assoc($queryListOfStudents)){
                    $studentResultArray = array();
                    $gpa=0;
                    $totalGradePoints=0;
                    $totalUnits=0;
                    $gpa=0;
                    $f_s_failedCourses="";
                    $s_s_failedCourses = "";
                    $repeats =0;
                    $remarks ="";

                    $studentResultArray[]=$student_id['matno'];
                    while($course = mysqli_fetch_assoc($newCourses)){           //points to note
                        $result = mysqli_query($conn,"SELECT score, units FROM course_registration, course WHERE course_registration.code=course.code AND course.semester = '".$semester_in."' AND course_registration.level =".$level." AND course_registration.matno ='".$student_id['matno']."' AND course_registration.code= '".$course['code']."' AND course_registration.session=".$session."")or die(mysqli_error($conn));
                        $courseBasedResult = $result;
                        $scoreGradeString ="";
                        while($s_result = mysqli_fetch_assoc($result)){
                            $totalUnits+=$s_result['units'];
                            $scoreGradeString=$s_result['score']." ".getStudentGrade($s_result['score'])." ".getGradePoint($s_result['units'], getCourseGrade(getStudentGrade($s_result['score'])));
                            $totalGradePoints+=getGradePoint($s_result['units'], getCourseGrade(getStudentGrade($s_result['score'])));
                        }
                        $studentResultArray[] = $scoreGradeString;
                        // return $result->score;
                    }
                    $cumUnits+=$totalUnits;
                    $cgp+=$totalGradePoints;
                    /**************** Get failed courses for first semester************************/

                    if($semester=='First'){
                        $queryFailedCourses = mysqli_query($conn,"SELECT course_registration.code FROM course_registration, course WHERE course.code = course_registration.code AND course.semester='".$semester_in."' AND course_registration.level =".$level." AND course_registration.matno='".$student_id['matno']."' AND course_registration.session=".$session." AND score<45")or die(mysqli_error($conn));
                        if(mysqli_num_rows($queryFailedCourses)>0){
                            $repeats+=mysqli_num_rows($queryFailedCourses);
                            $remarks="R".$repeats;
                            while($failedCourse = mysqli_fetch_assoc($queryFailedCourses)){
                                $f_s_failedCourses.=$failedCourse['code'];
                            }
                        }else{
                            $f_s_failedCourses.="NIL";
                            $remarks = "CS";
                        }
                        $s_s_failedCourses="NIL";

                    }

                    if($semester=='Second'){
                        $previousResult = mysqli_query($conn,"SELECT score, units FROM course_registration, course WHERE course_registration.code=course.code AND course.semester ='First' AND course_registration.level =".$level." AND course_registration.matno ='".$student_id['matno']."' AND course_registration.session=".$session."")or die(mysqli_error($conn));
                        // $courseBasedPrevResult = $previousResult;
                        while($p_result = mysqli_fetch_assoc($previousResult)){
                            $cumUnits+=$p_result['units'];
                            $cgp+=getGradePoint($p_result['units'], getCourseGrade(getStudentGrade($p_result['score'])));
                        }
                        /*************Get failed courses if semester is second semester **********/
                        $queryFailedCourses = mysqli_query($conn,"SELECT course_registration.code FROM course_registration, course WHERE course.code = course_registration.code AND course.semester='First' AND course_registration.level =".$level." AND course_registration.matno='".$student_id['matno']."' AND course_registration.session=".$session." AND score<45")or die(mysqli_error($conn));
                        if(mysqli_num_rows($queryFailedCourses)>0){
                            $repeats+=mysqli_num_rows($queryFailedCourses);
                            $remarks="R".$repeats;
                            while($failedCourse = mysqli_fetch_assoc($queryFailedCourses)){
                                $f_s_failedCourses.=$failedCourse['code'];
                            }
                        }else{
                            $f_s_failedCourses.="NIL";
                            $remarks = "CS";
                        }
                        $queryS_FailedCourses = mysqli_query($conn,"SELECT course_registration.code FROM course_registration, course WHERE course.code = course_registration.code AND course.semester='Second' AND course_registration.level =".$level." AND course_registration.matno='".$student_id['matno']."' AND course_registration.session=".$session." AND score<45")or die(mysqli_error($conn));
                        if(mysqli_num_rows($queryS_FailedCourses)>0){
                            $repeats+=mysqli_num_rows($queryS_FailedCourses);
                            $remarks="R".$repeats;
                            while($failedCourse = mysqli_fetch_assoc($queryS_FailedCourses)){
                                $s_s_failedCourses.=$failedCourse['code'];
                            }
                        }else{
                            $s_s_failedCourses="NIL";
                        }

                    }


                    /**
                     * Total Grade Point is same as Cummulative Grade Point for 100 level.
                     * Grade Point Averge (GPA) is same as Cummulative Grade Point Average (CGPA) for 100 level
                     */
                    $gpa = $totalGradePoints/$totalUnits;
                    $cgpa = $cgp/$cumUnits;
                    $studentResultArray[]=$totalUnits;
                    $studentResultArray[]=$totalGradePoints;
                    $studentResultArray[]=number_format((float)$gpa,'2','.','');
                    $studentResultArray[]=$cumUnits;
                    $studentResultArray[]=$cgp;
                    $studentResultArray[]=number_format((float)$cgpa,'2','.','');
                    $studentResultArray[]=$f_s_failedCourses;
                    $studentResultArray[]=$s_s_failedCourses;
                    $studentResultArray[]=$remarks;

                    $resultArray[]=$studentResultArray;
                    $cgp=0;
                    $cumUnits=0;
                }

            }else{
                $queryListOfStudents = mysqli_query($conn,"SELECT DISTINCT matno FROM course_registration, course WHERE course_registration.code=course.code AND course.semester='".$semester."' AND level =".$level." AND course_registration.session=".$session."") or die(mysqli_error($conn));
                $listOfStudent = $queryListOfStudents;
                while($student_id = musqli_fetch_assoc($queryListOfStudents)){
                    $studentResultArray = array();
                    $totalGradePoints=0;
                    $totalUnits=0;
                    $gpa=0;
                    $f_s_failedCourses="";
                    $s_s_failedCourses = "";
                    $repeats =0;
                    $totalDropCourse = 0;
                    $remarks ="";
                    $dropCourse ="";
                    $secondSemesterDropCourse = "";
                    $carryOvers=0;

                    $cumGradePoint=0;

                    $studentResultArray[]=$student_id['matno'];
                    while($course = mysqli_fetch_assoc($queryNewCourses)){
                        $result = mysqli_query($conn,"SELECT score, units FROM course_registration, course WHERE course_registration.code=course.code AND course.semester = '".$semester."' AND course_registration.level =".$level." AND course_registration.matno ='".$student_id['matno']."' AND course_registration.code= '".$course['code']."' AND course_registration.session=".$session."")or die(mysqli_error($conn));
                        // $courseBasedResult = $result;
                        $scoreGradeString ="";
                        while($s_result = mysqli_fetch_assoc($result)){
                            $totalUnits+=$s_result['units'];
                            $scoreGradeString=$s_result['score']." ".getStudentGrade($s_result['score'])." ".getGradePoint($s_result['units'], getCourseGrade(getStudentGrade($s_result['score'])));
                            $totalGradePoints+=getGradePoint($s_result['units'], getCourseGrade(getStudentGrade($s_result['score'])));
                        }
                        $studentResultArray[] = $scoreGradeString;
                        // return $result->score;
                    }
                    while($r_course = mysqli_fetch_assoc($repeatedCourses)){
                        $result = mysqli_query($conn,"SELECT score, units FROM course_registration, course WHERE course_registration.code=course.code AND course.level_taken<course_registration.level AND course.semester = '".$semester."' AND course_registration.level =".$level." AND course_registration.matno ='".$student_id['matno']."' AND course_registration.code= '".$r_course['code']."' AND course_registration.session=".$session."") or die(mysqli_error($conn));
                        // $courseBasedResult = $result;
                        $scoreGradeString ="";
                        while($s_result = mysqli_fetch_assoc($result)){
                            $totalUnits+=$s_result['units'];
                            $scoreGradeString=$s_result['score']." ".getStudentGrade($s_result['score'])." ".getGradePoint($s_result['units'], getCourseGrade(getStudentGrade($s_result['score'])));
                            $totalGradePoints+=getGradePoint($s_result['units'], getCourseGrade(getStudentGrade($s_result['score'])));
                        }
                        $studentResultArray[] = $scoreGradeString;
                    }
                    $cumUnits+=$totalUnits;
                    $cumGradePoint+=$totalGradePoints;
                    $gpa = $totalGradePoints/$totalUnits;
                    /**************Implementing the cummulatives for levels higher than 100 level**************** */
                    if($semester=='First'){
                        $queryLowerResult = mysqli_query($conn,"SELECT score, units FROM course_registration, student, course WHERE student.matno = course_registration.matno AND
                        course.code = course_registration.code AND course_registration.matno = '".$student_id['matno']."' AND course_registration.level < ".$level."") or die(mysqli_error($conn));
                        while($lowerResult = mysqli_fetch_assoc($queryLowerResult)){
                            $cumUnits+=$lowerResult['units'];
                            $cumGradePoint+=getGradePoint($lowerResult['units'], getCourseGrade(getStudentGrade($lowerResult['score'])));
                        }
                        /***********************Query failed courses******************/
                        $queryFailedCourses = mysqli_query($conn,"SELECT course_registration.code FROM course_registration, course WHERE course.code = course_registration.code AND course.semester='First' AND course_registration.level =".$level." AND course_registration.matno='".$student_id['matno']."' AND course_registration.session=".$session."AND score<45");
                        if(mysqli_num_rows($queryFailedCourses)>0){
                            $repeats+=mysqli_num_rows($queryFailedCourses);
                            $remarks="R".$repeats;
                            while($failedCourse = mysqli_fetch_assoc($queryFailedCourses)){
                                $f_s_failedCourses.=$failedCourse['code'];
                            }
                        }else{
                            $f_s_failedCourses.="";
                            $remarks = "CS";
                        }
                        $s_s_failedCourses="NIL";

                        /*****************Query dropped courses for first semester**************/
                        $queryCurrentLevelCourses = mysqli_query($conn,"SELECT code FROM course WHERE semester='First' AND level_taken=".$level."");
                        while($c_l_course = mysqlil_fetch_assoc($queryCurrentLevelCourses)){
                            $queryDroppedCourses = mysqli_query($conn,"SELECT course_registration.code FROM course_registration, course WHERE course_registration.code=course.code AND course_registration.level=course.level_taken  AND course_registration.matno='".$student_id->matno."' AND course.semester='".$semester."' AND course_registration.level = ".$level." AND course.code ='".$c_l_course['code']."'")or die(mysqli_error($conn));
                            if(mysqli_num_rows($queryDroppedCourses)>0){
                                $dropCourse="";
                            }else{
                                $dropCourse.=$c_l_course->code;
                                $totalDropCourse++;

                            }
                        }
                        $f_s_failedCourses.=$dropCourse;
                        $carryOvers = $totalDropCourse;

                        if($f_s_failedCourses==""){
                            $f_s_failedCourses="NIL";
                        }

                        if($repeats>0 && $carryOvers>0){
                            $remarks="C".$carryOvers."R".$repeats;
                        }
                        if($repeats>0 && $carryOvers==0){
                            $remarks="R".$repeats;
                        }
                        if($repeats==0 && $carryOvers>0){
                            $remarks="C".$carryOvers;
                        }
                        if($repeats==0 && $carryOvers==0){
                            $remarks="CS";
                        }
                    }else{
                        /**************compute cummulative for second semester result*************/
                        $queryLowerResult = mysqli_query($conn,"SELECT score, units FROM course_registration, student, course WHERE student.matno = course_registration.matno AND
                        course.code = course_registration.code AND course_registration.matno = '".$student_id['matno']."' AND course_registration.level < ".$level."")or die(mysqli_error($conn));
                        while($lowerResult = mysqli_fetch_array($queryLowerResult)){
                            $cumUnits+=$lowerResult['units'];
                            $cumGradePoint+=getGradePoint($lowerResult['units'], getCourseGrade(getStudentGrade($lowerResult['score'])));
                        }
                        /***********************Query failed courses for first semester******************/
                        $queryFailedCourses = mysqli_query($conn,"SELECT course_registration.code FROM course_registration, course WHERE course.code = course_registration.code AND course.semester='First' AND course_registration.level =".$level." AND course_registration.matno='".$student_id->matno."' AND course_registration.session=".$session." AND score<45") or die(mysqli_error($conn));
                        if(mysqli_num_rows($queryFailedCourses)>0){
                            $repeats+=mysqli_num_rows($queryFailedCourses);
                            $remarks="R".$repeats;
                            foreach($queryFailedCourses as $failedCourse){
                                $f_s_failedCourses.=$failedCourse['code'];
                            }
                        }else{
                            $f_s_failedCourses.="";
                            $remarks = "CS";
                        }
                        $s_s_failedCourses="NIL";

                        /*****************Query dropped courses for first semester**************/
                        $queryCurrentLevelCourses = mysqli_query($conn,"SELECT code FROM course WHERE semester='First' AND level_taken=".$level."") or die(mysqli_error($conn));
                        while($c_l_course = mysqli_fetch_assoc($queryCurrentLevelCourses)){
                            $queryDroppedCourses = mysqli_query($conn,"SELECT course_registration.code FROM course_registration, course WHERE course_registration.code=course.code AND course_registration.level=course.level_taken  AND course_registration.matno='".$student_id['matno']."' AND course.semester='".$semester."' AND course_registration.level = ".$level." AND course.code ='".$c_l_course['code']."'") or die(mysqli_error($conn));
                            if(mysqli_vum_rows($queryDroppedCourses)>0){
                                $dropCourse="";
                            }else{
                                $dropCourse.=$c_l_course['code'];
                                $totalDropCourse++;
                            }
                        }
                        $f_s_failedCourses.=$dropCourse;
                        $carryOvers = $totalDropCourse;

                        if($f_s_failedCourses==""){
                            $f_s_failedCourses="NIL";
                        }

                        /**************Query failed courses for second semester**************/
                        $queryS_FailedCourses = mysqli_query($conn,"SELECT course_registration.code FROM course_registration, course WHERE course.code = course_registration.code AND course.semester='Second' AND course_registration.level =".$level." AND course_registration.matno='".$student_id['matno']."' AND course_registration.session=".$session." AND score<45");
                        if(mysqli_num_rows($queryS_FailedCourses)>0){
                            $repeats+=mysqli_num_rows($queryS_FailedCourses);
                            while($f_course = mysqli_fetch_assoc($queryS_FailedCourses)){
                                $s_s_failedCourses.=$f_course['code'];
                            }

                        }else{
                            $s_s_failedCourses="";
                        }

                        /************************Query dropped courses for second semester********************/
                        $queryS_semester_level_courses = mysqli_query($conn,"SELECT code FROM course WHERE semester='Second' AND level_taken=".$level."")or die(mysqli_error($conn));
                        foreach($queryS_semester_level_courses as $course){
                            $queryS_semester_drop_course = mysqli_query($conn,"SELECT course_registration.code FROM course_registration, course WHERE course_registration.code = course.code AND
                            course_registration.level=course.level_taken AND course_registration.matno='".$student_id['matno']."' AND course.semester='Second' AND course_registration.level =".$level." AND course.code ='".$course['code']."'")or die(mysqli_error($conn));
                            if(mysqli_num_rows($queryS_semester_drop_course)>0){
                                $secondSemesterDropCourse="";
                            }else{
                                $secondSemesterDropCourse.=$course['code'];
                                $totalDropCourse++;
                            }
                        }
                        $s_s_failedCourses.=$secondSemesterDropCourse;
                        $carryOvers=$totalDropCourse;
                        if($s_s_failedCourses==""){
                            $s_s_failedCourses="NIL";
                        }
                        if($repeats>0 && $carryOvers>0){
                            $remarks="C".$carryOvers."R".$repeats;
                        }
                        if($repeats==0 && $carryOvers>0){
                            $remarks="C".$carryOvers;
                        }
                        if($repeats>0 && $carryOvers==0){
                            $remarks="R".$repeats;
                        }
                        if($repeats==0 && $carryOvers==0){
                            $remarks="CS";
                        }
                    }
                    $gpa = $totalGradePoints/$totalUnits;
                    $cgpa = $cumGradePoint/$cumUnits;
                    $studentResultArray[]=$totalUnits;
                    $studentResultArray[]=$totalGradePoints;
                    $studentResultArray[]=number_format((float)$gpa,'2','.','');
                    $studentResultArray[]=$cumUnits;
                    $studentResultArray[]=$cumGradePoint;
                    $studentResultArray[]=number_format((float)$cgpa,'2','.','');
                    $studentResultArray[]=$f_s_failedCourses;
                    $studentResultArray[]=$s_s_failedCourses;
                    $studentResultArray[]=$remarks;

                    $resultArray[]=$studentResultArray;
                    $cumGradePoint=0;
                    $cumUnits=0;

                }
            }
            $data=[
                "allRegisteredCourses" => $courseCodes,
                "newCourses"=> $newCourses,
                "repeatedCourses"=> $repeatedCourses,
                "resultArray"=> $resultArray
            ];
            return json_encode($data);
        }
    }
    /************************** General Result Ends Here *****************************/

?>