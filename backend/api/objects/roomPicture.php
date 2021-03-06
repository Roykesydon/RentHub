<?php
class RoomPicture{
    // database connection and table name
    private $table_name = "roomPicture";
    private $conn;

    // properties
    public $room_ID;
    public $pictureURL_one;
    public $pictureURL_two;
    public $pictureURL_three;
    public $pictureURL_four;
    public $pictureURL_five;
    public $pictureURL_six;
    public $pictureURL_seven;
    public $pictureURL_eight;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function createRoomPicture()
    {
        try{
            // query to insert record
            $query = "INSERT INTO {$this->table_name} values(?,?,?,?,?,?,?,?,?);";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1,$this->room_ID);
            $stmt->bindParam(2,$this->pictureURL_one);
            $stmt->bindParam(3,$this->pictureURL_two);
            $stmt->bindParam(4,$this->pictureURL_three);
            $stmt->bindParam(5,$this->pictureURL_four);
            $stmt->bindParam(6,$this->pictureURL_five);
            $stmt->bindParam(7,$this->pictureURL_six);
            $stmt->bindParam(8,$this->pictureURL_seven);
            $stmt->bindParam(9,$this->pictureURL_eight);

            $stmt->execute();

            return true;
        }catch(PDOException $e)
        {            
            // tell the user
            // echo json_encode(array("success" => "0","message" => "Unable to create roomPicture."));
            throw $e;
        }
        // if($error)
        // {
        //     return true;
        // }
        // else
        // {
        //     $this->conn->rollBack();
        //     return false;
        // }
    }

    function updateRoomPicture()
    {
        try{
            // query to insert record
            $query = "UPDATE {$this->table_name} 
                        set `pictureURL_one` = ?,
                            `pictureURL_two` = ?,
                            `pictureURL_three` = ?,
                            `pictureURL_four` = ?,
                            `pictureURL_five` = ?,
                            `pictureURL_six` = ?,
                            `pictureURL_seven` = ?,
                            `pictureURL_eight` = ?
                      WHERE `room_ID` = ?;";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1,$this->pictureURL_one);
            $stmt->bindParam(2,$this->pictureURL_two);
            $stmt->bindParam(3,$this->pictureURL_three);
            $stmt->bindParam(4,$this->pictureURL_four);
            $stmt->bindParam(5,$this->pictureURL_five);
            $stmt->bindParam(6,$this->pictureURL_six);
            $stmt->bindParam(7,$this->pictureURL_seven);
            $stmt->bindParam(8,$this->pictureURL_eight);
            $stmt->bindParam(9,$this->room_ID);

            $stmt->execute();

            return true;
        }catch(PDOException $e)
        {            
            throw $e;
        }
    }

}
?>