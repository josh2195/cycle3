<?php
/**
 * Created by PhpStorm
 * User: jbedw
 * Date: 10/7/2019
 * Time: 3:16 PM
 */

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

$pagetitle = "CCU Campus Survey";
include_once "head.php";

//initiate variables
$showform = 1;
$errmsg = 0;
$erremail = "";
$errbuilding = "";
$errrestaurant = "";
$errhangout = "";
$errimprovements = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //Sanitize user data
    $formdata['email'] = trim(strtolower($_POST['email']));
    $formdata['building'] = trim($_POST['building']);
    $formdata['restaurant'] = trim($_POST['restaurant']);
    $formdata['hangout'] = trim($_POST['hangout']);
    $formdata['improvements'] = trim($_POST['improvements']);

    //Check for empty fields
    if (empty($formdata['email'])) {$erremail = "Email is required."; $errmsg = 1; }
    if (empty($formdata['building'])) {$errbuilding = "You must have a favorite building?!?!?!"; $errmsg = 1; }
    if (empty($formdata['restaurant'])) {$errrestaurant = "You don't like to eat anywhere?!?!?!"; $errmsg = 1; }
    if (empty($formdata['hangout'])) {$errhangout = "There must be somewhere you go to relax?!?!?!"; $errmsg = 1; }
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
            $sql = "INSERT INTO campussurvey (building, restaurant, hangout, improvements)
                    VALUES (:building, :restaurant, :hangout, :improvements) ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':building', $formdata['building']);
            $stmt->bindValue(':restaurant', $formdata['restaurant']);
            $stmt->bindValue(':hangout', $formdata['hangout']);
            $stmt->bindValue(':improvements', $formdata['improvements']);
            $stmt->execute();

            $showform =0;
            echo "<p class='awesome'>Thanks for submitting your survey! Feel free to do one of the others!! <li><a href=\"campussurvey.php\">CCU Campus Survey</a></li> <li><a href=\"classsurvey.php\">CCU Class Survey</a></li> <li><a href=\"sportssurvey.php\">CCU Sports Survey</a></li></p>";
        }
        catch (PDOException $e)
        {
            die( $e->getMessage() );
        }
    }
}

if($showform == 1) {
    ?>
    <form name="campus" id="campus" method="post" action="campussurvey.php">
        <table>
            <tr>
                <th><label for="email">Email:</label><span class="error">*</span></th>
                <td><input name="email" id="email" type="text" size="50"/>
                    <span class="error"><?php if (isset($erremail)) {echo $erremail;} ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="building">Favorite Building:</label><span class="error">*</span></th>
                <td><input name="building" id="building" type="text" size="50"/>
                    <span class="error"><?php if (isset($errbuilding)) {echo $errbuilding;} ?></span>
                </td>
            </tr>
            <tr>
                <th><label for="restaurant">Favorite Restaurant:</label><span class="error">*</span></th>
                <td><input name="restaurant" id="restaurant" type="text" size="50"/>
                    <span class="error"><?php if (isset($errrestaurant)) {echo $errrestaurant;} ?></span></td>
            </tr>
            <tr>
                <th><label for="hangout">Where do you like to hangout?</label><span class="error">*</span></th>
                <td><span class="error"><?php if (isset($errhangout)) {echo $errhangout;} ?></span>
                    <textarea name="hangout" id="hangout"
                              placeholder="Give a name or description of the place you like to hangout on campus."></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="improvements">What are some ways that we can improve your on-campus
                        experience?</label><span class="error">*</span></th>
                <td><span class="error"><?php if (isset($errimprovements)) {echo $errimprovements;} ?></span>
                    <textarea name="improvements" id="improvements"></textarea>
                </td>
            </tr>
            <tr><th><label for="submit">Submit:</label></th>
                <td><input type="submit" name="submit" id="submit" value="Submit"/></td>
            </tr>
        </table>
    </form>
    <p class="error">* Indicates required field</p>
    <?php
}
include_once "foot.php";