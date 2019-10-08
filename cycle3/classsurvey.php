<?php
/**
 * Created by PhpStorm
 * User: jbedw
 * Date: 10/7/2019
 * Time: 5:09 PM
 */

$pagetitle = "CCU Class Survey";
include_once "head.php";

//initiate variables
$showform = 1;
$errmsg = 0;
$erremail = "";
$errstudentyear = "";
$errmajor = "";
$errclasstaken = "";
$errhelpful = "";
$errimprovements = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //Sanitize user data
    $formdata['email'] = trim(strtolower($_POST['email']));
    $formdata['studentyear'] = trim($_POST['studentyear']);
    $formdata['major'] = trim($_POST['major']);
    $formdata['classtaken'] = trim($_POST['classtaken']);
    $formdata['helpful'] = trim($_POST['helpful']);
    $formdata['improvements'] = trim($_POST['improvements']);

    //Check for empty fields
    if (empty($formdata['email'])) {$erremail = "Email is required."; $errmsg = 1; }
    if (empty($formdata['studentyear'])) {$errstudentyear = "Please choose a year"; $errmsg = 1; }
    if (empty($formdata['major'])) {$errmajor = "Major is required"; $errmsg = 1; }
    if (empty($formdata['classtaken'])) {$errclasstaken = "There must have been one class that wasn't awful?!?!?!"; $errmsg = 1; }
    if (empty($formdata['helpful'])) {$errhelpful = "Nothing was helpful to you?!?!?!?!"; $errmsg = 1; }
    if (empty($formdata['improvements'])) {$errimprovements = "Everyone complains about parking! You can at least put that!"; $errmsg = 1; }

    //Checks for valid email
    if(filter_var($formdata['email'], FILTER_VALIDATE_EMAIL)){
    }
    else{
        $errmsg = 1;
        $erremail = "Email is invalid.";
    }

    //Error handling
    if($errmsg == 1)
    {
        echo "<p class='error'>What did you do wrong? Please fix the errors.</p>";
    }
    else{

        //Insert the data into the database
        try{
            $sql = "INSERT INTO classsurvey (studentyear, major, classtaken, helpful, improvements)
                    VALUES (:studentyear, :major, :classtaken, :helpful, :improvements) ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':studentyear', $formdata['studentyear']);
            $stmt->bindValue(':major', $formdata['major']);
            $stmt->bindValue(':classtaken', $formdata['classtaken']);
            $stmt->bindValue(':helpful', $formdata['helpful']);
            $stmt->bindValue(':improvements', $formdata['improvements']);
            $stmt->execute();

            $showform =0;
            echo "<p class='awesome'>Thanks for submitting your survey! Feel free to do one of the others!!<li><a href=\"campussurvey.php\">CCU Campus Survey</a></li> <li><a href=\"classsurvey.php\">CCU Class Survey</a></li> <li><a href=\"sportssurvey.php\">CCU Sports Survey</a></li></p>";
        }
        catch (PDOException $e)
        {
            die( $e->getMessage() );
        }
    }
}

if($showform == 1) {
    ?>
    <form name="classes" id="classes" method="post" action="classsurvey.php">
        <table>
            <tr>
                <th><label for="email">Email:</label><span class="error">*</span></th>
                <td><input name="email" id="email" type="text" size="50"/>
                    <span class="error"><?php if (isset($erremail)) {echo $erremail;} ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="studentyear">What is your year?</label><span class="error">*</span></th>
                <td><select name="studentyear" id="studentyear">
                        <option id="studentyear" value="freshman">Freshman</option>
                        <option id="studentyear" value="sophomore">Sophomore</option>
                        <option id="studentyear" value="junior">Junior</option>
                        <option id="studentyear" value="senior">Senior</option>
                    </select>
                    <span class="error"><?php if (isset($errstudentyear)) {echo $errstudentyear;} ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="major">What is your major?</label><span class="error">*</span></th>
                <td><input name="major" id="major" type="text" size="50"/>
                    <span class="error"><?php if (isset($errmajor)) {echo $errmajor;} ?></span></td>
            </tr>
            <tr>
                <th><label for="classtaken">What is your favorite class so far?</label><span class="error">*</span></th>
                <td><input name="classtaken" id="class" type="text" size="50"/>
                    <span class="error"><?php if (isset($errclass)) {echo $errclass;} ?></span></td>
                </td>
            </tr>
            <tr>
                <th><label for="helpful">What was something that a professor did that helped you be successful in class?</label><span class="error">*</span></th>
                <td><span class="error"><?php if (isset($errhelpful)) {echo $errhelpful;} ?></span>
                    <textarea name="helpful" id="helpful"></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="improvements">What are some ways that we can improve your in-class experience?</label><span class="error">*</span></th>
                <td><span class="error"><?php if (isset($errimprovements)) {echo $errimprovements;} ?></span>
                    <textarea name="improvements" id="improvements"></textarea>
                </td>
            </tr>
            <tr><th><label for="submit"></label></th>
                <td><input type="submit" name="submit" id="submit" value="submit"/>
                </td>
            </tr>
        </table>
    </form>
    <p class="error">* Indicates required field</p>
    <?php
}
include_once "foot.php";