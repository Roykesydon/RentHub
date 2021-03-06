<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function msg($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}

// INCLUDING DATABASE AND MAKING OBJECT
require_once './config/database.php';
$database = new Database();
$conn = $database->getConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));
$returnData = [];

// IF REQUEST METHOD IS NOT POST
if($_SERVER["REQUEST_METHOD"] != "POST"):
    $returnData = msg(0,404,'Page Not Found!');

// CHECKING EMPTY FIELDS
elseif(!isset($data->name)
    || !isset($data->id)
    || !isset($data->email)
    || !isset($data->password)
    || !isset($data->phoneNumber)
    || empty(trim($data->name))
    || empty(trim($data->id))
    || empty(trim($data->email))
    || empty(trim($data->password))
    || empty(trim($data->phoneNumber))
    ):

    $fields = ['fields' => ['name','id','email','password','phoneNumber']];
    $returnData = msg(0,422,'Please Fill in all Required Fields!',$fields);

// IF THERE ARE NO EMPTY FIELDS THEN-
else:
    
    // erase space
    $name = trim($data->name);
    $id = trim($data->id);
    $email = trim($data->email);
    $password = trim($data->password);
    $phoneNumber = trim($data->phoneNumber);

    // check valid input
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
        $returnData = msg(0,422,'Invalid Email Address!');
    
    elseif(strlen($password) < 8):
        $returnData = msg(0,422,'Your password must be at least 8 characters long!');

    elseif(strlen($name) < 3 || strlen($name) > 30):
        $returnData = msg(0,422,'name must be < 30 and > 2');

    elseif(strlen($id) < 3 || strlen($id) > 50):
        $returnData = msg(0,422,'id must be < 50 and > 2');

    elseif(strlen($phoneNumber) < 10 || strlen($phoneNumber) > 25):
        $returnData = msg(0,422,'phone number must be < 25 and > 9');

    else:
        try{
            // write into DB
            $check_email = "SELECT `email` FROM `user` WHERE `email`=:email";
            $check_email_stmt = $conn->prepare($check_email);
            $check_email_stmt->bindValue(':email', $email,PDO::PARAM_STR);
            $check_email_stmt->execute();

            $check_userID = "SELECT `user_ID` FROM `user` WHERE `user_ID`=:id";
            $check_userID_stmt = $conn->prepare($check_userID);
            $check_userID_stmt->bindValue(':id', $id,PDO::PARAM_STR);
            $check_userID_stmt->execute();

            if($check_email_stmt->rowCount()):
                $returnData = msg(0,422, 'This E-mail already in use!');
            elseif($check_userID_stmt->rowCount()):
                $returnData = msg(0,422, 'This user_ID already in use!');
            
            else:
                $insert_query = "INSERT INTO `user`(`user_ID`, `user_name`, `password`, `email`, `phone_number`, `access_key`, `timestamp`) VALUES(:id,:name,:password,:email,:phoneNumber,null,null)";

                $insert_stmt = $conn->prepare($insert_query);

                // DATA BINDING
                $insert_stmt->bindValue(':id', htmlspecialchars(strip_tags($id)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($name)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':email', $email,PDO::PARAM_STR);
                $insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT),PDO::PARAM_STR);
                $insert_stmt->bindValue(':phoneNumber', htmlspecialchars(strip_tags($phoneNumber)),PDO::PARAM_STR);

                $insert_stmt->execute();

                $returnData = msg(1,201,'You have successfully registered.');

            endif;

        }
        catch(PDOException $e){
            $returnData = msg(0,500,$e->getMessage());
        }
    endif;
    
endif;

echo json_encode($returnData);
?>