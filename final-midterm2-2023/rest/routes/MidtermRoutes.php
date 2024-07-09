<?php
require_once __DIR__ . '/../services/MidtermService.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('midterm_service', new MidtermService());
Flight::route('GET /midterm/connection-check', function(){
    /** TODO
    * This endpoint prints the message from constructor within MidtermDao class
    * Goal is to check whether connection is successfully established or not
    * This endpoint does not have to return output in JSON format
    * Sample output is given in figure 2
    */
});

Flight::route('POST /midterm/investor', function(){
    $data = Flight::request()->data;

    $first_name = $data->first_name;
    $last_name = $data->last_name;
    $email = $data->email;
    $company = $data->company;
    $share_class_id = $data->share_class_id;
    $share_class_category_id = $data->share_class_category_id;
    $diluted_shares = $data->diluted_shares;

    $service = new MidtermService();
    $result = $service->addInvestor($first_name, $last_name, $email, $company, $share_class_id, $share_class_category_id, $diluted_shares);

    Flight::json($result);
});



Flight::route('GET /midterm/investor_email/@email', function($email){
    /** TODO
    * This endpoint is used to check if investor email is in valid format
    * and if it exists in investors table
    * If format is not valid, output should be 'Invalid email format' message
    * If format is valid, return either
    * 'Investor first_name last_name' uses this email address' (replace first_name and last_name with data from database)
    * or 'Investor with this email does not exists in database'
    * Output example is given in figure 2 (message should be updated according to the result)
    * This endpoint should return output in JSON format
    */
    $result = Flight::get('midterm_service')->investor_email($email);
    Flight::json($result);
});

Flight::route("GET /midterm/investor/@share_class_id", function($share_class_id){
    /** TODO
    * This endpoint is used to list all investors from give share_class
    * (meaning all investors occuring in cap table with given share_class_id)
    * It should return share class description, equiy main currency, price and authorized_assets,
    * investor first and last name, email, company and total diluted assets within cap table
    * Sample data within tables and expected output with given data is provided in figures 3, 4, 5 and 6
    * Output is given in figure 6
    * This endpoint should return output in JSON format
    */
    $result = Flight::get('midterm_service')->investors($share_class_id);
    Flight::json($result);
});

?>
