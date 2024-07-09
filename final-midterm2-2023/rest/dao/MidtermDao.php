<?php
require_once "BaseDao.php";

class MidtermDao extends BaseDao {

    public function __construct(){
        parent::__construct();
    }

    public function addInvestor($first_name, $last_name, $email, $company) {
        $stmt = $this->connection->prepare("INSERT INTO investors (first_name, last_name, email, company) VALUES (?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $email, $company]);
        return $this->connection->lastInsertId();
    }

    public function addCapTableEntry($share_class_id, $share_class_category_id, $investor_id, $diluted_shares) {
        $stmt = $this->connection->prepare("INSERT INTO cap_table (share_class_id, share_class_category_id, investor_id, diluted_shares) VALUES (?, ?, ?, ?)");
        $stmt->execute([$share_class_id, $share_class_category_id, $investor_id, $diluted_shares]);
    }

    public function getInvestorByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM investors WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function getAuthorizedAssets($share_class_id) {
        $stmt = $this->connection->prepare("SELECT authorized_assets FROM share_classes WHERE id = ?");
        $stmt->execute([$share_class_id]);
        return $stmt->fetchColumn();
    }

    public function getTotalDilutedShares($share_class_id) {
        $stmt = $this->connection->prepare("SELECT SUM(diluted_shares) as total FROM cap_table WHERE share_class_id = ?");
        $stmt->execute([$share_class_id]);
        return $stmt->fetchColumn();
    }


    /** TODO
    * Implement DAO method to validate email format and check if email exists
    */
    public function investor_email($email){
        $query = "SELECT first_name, last_name FROM users WHERE email = :email";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /** TODO
    * Implement DAO method to return list of investors according to instruction in MidtermRoutes.php
    */
    public function investors($share_class_id) {
        $query = "
            SELECT 
                sc.description as share_class_description, 
                sc.equity_main_currency, 
                sc.price, 
                sc.authorized_assets,
                i.first_name, 
                i.last_name, 
                i.email, 
                i.company, 
                SUM(ct.diluted_shares) as total_diluted_assets
            FROM 
                share_classes sc
                JOIN cap_table ct ON sc.id = ct.share_class_id
                JOIN investors i ON ct.investor_id = i.id
            WHERE 
                sc.id = :share_class_id
            GROUP BY 
            sc.description, 
            sc.equity_main_currency, 
            sc.price, 
            sc.authorized_assets, 
            i.first_name, 
            i.last_name, 
            i.email, 
            i.company
        ";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':share_class_id', $share_class_id);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
