<!DOCTYPE html>
<HTML lang="de">
<HEAD>
    <link rel="stylesheet" href="css/profile_style.css">
    <script src="js/groupSettings_js.js"></script>
</HEAD>
<BODY>
<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

if (isset($_COOKIE["chat_id"])) {
    $chat_id = $_COOKIE["chat_id"];
    echo "<div id='Content'>";
    $groupInfo = mysqli_query($db, "SELECT groupname, portrait FROM chats WHERE id = $chat_id");
    if (mysqli_num_rows($groupInfo)) {
        $groupInfoRow = mysqli_fetch_object($groupInfo);
        $groupname = $groupInfoRow->groupname;
        $portrait = $groupInfoRow->portrait;

        $fullPortrait = "../data/groupimages/" . $portrait;
        if (!file_exists("../" . $fullPortrait) || $portrait == "" || !$visibility) {
            $fullPortrait = "../data/portraits/default.png";
        }

        echo "<div id='portrait'>";
        echo "<img src='$fullPortrait' id='portraitImage'/>";
        echo "</div>";
        echo "<div id='portraitBackground'>";
        echo "<br/>";
        echo "<span id='profileText'>$groupname</span>";
        echo "<br/>";

        echo "</div>";

        $groupMembers = mysqli_query($db, "SELECT users.id, users.firstname, users.lastname, users.portrait FROM groupmembers LEFT JOIN users ON users.id = groupmembers.user_id WHERE groupmembers.chat_id = $chat_id && groupmembers.admin ORDER BY users.firstname, users.lastname");
        if (mysqli_num_rows($groupMembers)) {
            echo "<br/>";
            echo "<div id='groupMembers'>";
            echo "<span class='infoText''>Gruppenadministratoren:</span>";
            echo "<br/>";
            echo "<div id='groupAdministrators'>";
            while ($groupMembersRows = mysqli_fetch_object($groupMembers)) {
                $member_id = $groupMembersRows->id;
                $firstname = $groupMembersRows->firstname;
                $lastname = $groupMembersRows->lastname;
                $portrait = $groupMembersRows->portrait;

                $fullPortrait = "../data/portraits/" . $portrait;
                if (!file_exists("../" . $fullPortrait) || $portrait == "") {
                    $fullPortrait = "../data/portraits/default.png";
                }

                if ($member_id == $user_id) {
                    echo "<div id='member" . $member_id . "' class='chip chipAdministrator chipMeAdministrator'><img src='" . $fullPortrait . "'/> <span>Ich</span></div>";
                } else {
                    echo "<div id='member" . $member_id . "' class='chip chipAdministrator'><img src='" . $fullPortrait . "'/> <span>" . $firstname . " " . $lastname . " <i class='material-icons-thin groupMemberDelete'>close</i></span></div>";
                }
            }
            echo "</div>";


            //echo "<div class='chipEmptyAdministrators'></div>";
            echo "<br/>";
            echo "<br/>";
            echo "<span class='infoText''>Gruppenmitglieder:</span>";
            echo "<br/>";
            //echo "</div>";
            //echo "<div class='chipEmptyMembers''></div>";
            //echo "<input type='text' id='groupMemberSearch' autofocus/>";
            echo "<div id='friendSuggestions'></div>";
            $groupMembers = mysqli_query($db, "SELECT users.id, users.firstname, users.lastname, users.portrait FROM groupmembers LEFT JOIN users ON users.id = groupmembers.user_id WHERE groupmembers.chat_id = $chat_id && groupmembers.admin = false ORDER BY users.firstname, users.lastname");
            if (mysqli_num_rows($groupMembers)) {
                while ($groupMembersRows = mysqli_fetch_object($groupMembers)) {
                    $member_id = $groupMembersRows->id;
                    $firstname = $groupMembersRows->firstname;
                    $lastname = $groupMembersRows->lastname;

                    $portrait = $groupMembersRows->portrait;
                    $fullPortrait = "../data/portraits/" . $portrait;
                    if (!file_exists("../" . $fullPortrait) || $portrait == "") {
                        $fullPortrait = "../data/portraits/default.png";
                    }
                    if ($member_id == $user_id) {
                        echo "<div id='member" . $member_id . "' class='chip chipMeMember'><img src='" . $fullPortrait . "'/> <span>Ich</span></div>";
                    } else {
                        echo "<div id='member" . $member_id . "' class='chip'><img src='" . $fullPortrait . "'/> <span>" . $firstname . " " . $lastname . " <i class='material-icons-thin groupMemberDelete'>close</i></span></div>";
                    }
                }
            } else {
                echo "Keine Mitglieder";
            }
        }

    }
} else {
    echo "error";
}
?>
</BODY>
</HTML>