<?php
require_once __DIR__."/../dao/MidtermDao.php";

class MidtermService {
    protected $dao;

    public function __construct(){
        $this->dao = new MidtermDao();
    }

    public function addInvestor($first_name, $last_name, $email, $company, $share_class_id, $share_class_category_id, $diluted_shares) {
        // Rule 2: Check if email is unique
        if ($this->dao->getInvestorByEmail($email)) {
            return ["status" => "error", "message" => "Email address already exists"];
        }

        // Rule 1: Check share dilution limit
        $authorized_assets = $this->dao->getAuthorizedAssets($share_class_id);
        $total_diluted_shares = $this->dao->getTotalDilutedShares($share_class_id);

        if (($total_diluted_shares + $diluted_shares) > $authorized_assets) {
            return ["status" => "error", "message" => "Diluted shares exceed authorized assets"];
        }

        // Add investor
        $investor_id = $this->dao->addInvestor($first_name, $last_name, $email, $company);

        // Add cap-table entry
        $this->dao->addCapTableEntry($share_class_id, $share_class_category_id, $investor_id, $diluted_shares);

        return ["status" => "success", "message" => "Investor has been created successfully"];
    }


    /** TODO
    * Implement service method to validate email format and check if email exists
    */
    public function investor_email($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['message' => 'Invalid email format'];
        }

        $user = $this->dao->investor_email($email);

        if ($user) {
            return ['message' => 'Investor ' . $user['first_name'] . ' ' . $user['last_name'] . ' uses this email address'];
        } else {
            return ['message' => 'Investor with this email does not exist in database'];
        }
    }

    /** TODO
    * Implement service method to return list of investors according to instruction in MidtermRoutes.php
    */
    public function investors($share_class_id) {
        return $this->dao->investors($share_class_id);
    }
}
?>
