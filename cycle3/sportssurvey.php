<?php
/**
 * Created by PhpStorm
 * User: jbedw
 * Date: 10/7/2019
 * Time: 4:32 PM
 */

$pagetitle = "CCU Sports Survey";
include_once "head.php";

//initiate variables
$showform = 1;
$errmsg = 0;
$erremail = "";
$errsport = "";
$errwhy = "";
$errswhy = "";
$errattend = "";
$errimprovements = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //Sanitize user data
    $formdata['email'] = trim(strtolower($_POST['email']));
    $formdata['sport'] = trim($_POST['sport']);
    $formdata['sportwhy'] = trim($_POST['sportwhy']);
    $formdata['attend'] = trim($_POST['attend']);
    $formdata['attendwhy'] = trim($_POST['attendwhy']);
    $formdata['improvements'] = trim($_POST['improvements']);


    //Check for empty fields
    if (empty($formdata['email'])) {$erremail = "Email is required."; $errmsg = 1; }
    if (empty($formdata['sport'])) {$errsport = "Please enter a sport. If you don't like sports, put that!"; $errmsg = 1; }
    if (empty($formdata['sportwhy'])) {$errwhy = "You don't know why you dont like your favorite sport?"; $errmsg = 1; }
    if (empty($formdata['attend'])) {$errattend = "Choose one please."; $errmsg = 1; }
    if (empty($formdata['attendwhy'])) {$errswhy = "There must be a reason you went to a game???"; $errmsg = 1; }
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
            $sql = "INSERT INTO sportssurvey (sport, sportwhy, attend, attendwhy, improvements)
                    VALUES (:sport, :sportwhy, :attend, :attendwhy, :improvements) ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':sport', $formdata['sport']);
            $stmt->bindValue(':sportwhy', $formdata['sportwhy']);
            $stmt->bindValue(':attend', $formdata['attend']);
            $stmt->bindValue(':attendwhy', $formdata['attendwhy']);
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
    <form name="sport" id="sport" method="post" action="sportssurvey.php">
        <table>
            <tr>
                <th><label for="email">Email:</label><span class="error">*</span></th>
                <td><input name="email" id="email" type="text" size="50"/>
                    <span class="error"><?php if (isset($erremail)) {echo $erremail;} ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="sport">Favorite Sport:</label><span class="error">*</span></th>
                <td><input name="sport" id="sport" type="text" size="50"/>
                    <span class="error"><?php if (isset($errsport)) {echo $errsport;} ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="sportwhy">Why is this your favorite sport?</label><span class="error">*</span></th>
                <td><input name="sportwhy" id="sportwhy" type="text" size="50" placeholder="Max. 50 characters"/>
                    <span class="error"><?php if (isset($errwhy)) {echo $errswhy;} ?></span></td>
            </tr>
            <tr>
                <th><label for="attend">Have you attended a sporting event at Coastal?</label><span class="error">*</span></th>
                <td><input type="radio" name="attend" value="yes"> Yes<br>
                    <input type="radio" name="attend" value="no"> No<br>
                    <span class="error"><?php if (isset($errhangout)) {echo $errhangout;} ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="attendwhy">What is your reason for attending/not attending this event?</label><span class="error">*</span></th>
                <td><input name="attendwhy" id="attendwhy" type="text" size="50" placeholder="Max. 50 characters"/>
                    <span class="error"><?php if (isset($errwhy)) {echo $errwhy;} ?></span></td>
            </tr>
            <tr>
                <th><label for="improvements">What are some ways that we can improve your on-campus experience?</label><span class="error">*</span></th>
                <td><span class="error"><?php if (isset($errimprovements)) {echo $errimprovements;} ?></span>
                    <textarea name="improvements" id="improvements"></textarea>
                </td>
            </tr>
            <tr><th><label for="submit"></label></th>
                <td><input type="submit" name="submit" id="submit" value="submit"/></td>
            </tr>
        </table>
    </form>
    <p class="error">* Indicates required field</p>
    <?php
}
include_once "foot.php";