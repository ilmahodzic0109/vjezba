<?php
require_once "BaseDao.php";

class FinalDao extends BaseDao {

    public function __construct(){
        parent::__construct('users');
    }

    /** TODO
    * Implement DAO method used login user
    */
    public function login($email) {
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /** TODO
    * Implement DAO method used add new investor to investor table and cap-table
    */
    public function investor($first_name, $last_name, $email, $company, $share_class_id, $share_class_category_id, $diluted_shares) {
        // Check if email is unique
        $check_query = "SELECT * FROM investors WHERE email = :email";
        $existing_investor = $this->query_unique($check_query, ['email' => $email]);

        if ($existing_investor) {
            throw new Exception("Investor with this email already exists.");
        }

        // Check if diluted shares are within limits
        $share_class_query = "SELECT authorized_assets FROM share_classes WHERE id = :id";
        $share_class = $this->query_unique($share_class_query, ['id' => $id]);

        $cap_table_query = "SELECT SUM(diluted_shares) as total_diluted FROM cap_table WHERE share_class_id = :share_class_id";
        $cap_table = $this->query_unique($cap_table_query, ['share_class_id' => $share_class_id]);

        if ($cap_table['total_diluted'] + $diluted_shares > $share_class['authorized_assets']) {
            throw new Exception("Diluted shares exceed authorized assets for this share class.");
        }

        // Insert into investors table
        $insert_investor_query = "INSERT INTO investors (first_name, last_name, email, company) VALUES (:first_name, :last_name, :email, :company)";
        $this->execute_update($insert_investor_query, [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'company' => $company
        ]);

        // Get investor ID
        $investor_id = $this->lastInsertId();

        // Insert into cap_table
        $insert_cap_table_query = "INSERT INTO cap_table (share_class_id, share_class_category_id, investor_id, diluted_shares) VALUES (:share_class_id, :share_class_category_id, :investor_id, :diluted_shares)";
        $this->execute_update($insert_cap_table_query, [
            'share_class_id' => $share_class_id,
            'share_class_category_id' => $share_class_category_id,
            'investor_id' => $investor_id,
            'diluted_shares' => $diluted_shares
        ]);
    }

    public function share_classes() {
        $query = "SELECT * FROM share_classes";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function share_class_categories() {
        $query = "SELECT * FROM share_class_categories";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>