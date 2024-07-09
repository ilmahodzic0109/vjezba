<?php
require_once __DIR__ . '/../services/FinalService.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('final_service', new FinalService());

Flight::route('GET /final/connection-check', function(){
    /** TODO
    * This endpoint prints the message from constructor within MidtermDao class
    * Goal is to check whether connection is successfully established or not
    * This endpoint does not have to return output in JSON format
    */
    echo "Connected successfully";
    $dao = new BaseDao();
});

Flight::route('GET /final/login', function(){
    /** TODO
    * This endpoint is used to login user to system
    * you can use email: demo.user@gmail.com and password: 123 to login
    * Output should be array containing a success message
    * This endpoint should return output in JSON format
    */
    $email = Flight::request()->query->email;
    $password = Flight::request()->query->password;

    if (!isset($email) || !isset($password)) {
        Flight::halt(400, "Email and password are required");
    }

    $user = Flight::get('final_service')->login($email);

    if (!$user || $password != $user['password']) {
        Flight::halt(401, "Invalid username or password");
    }

    // Login successful, include the token in the response
    $response = [
        'message' => 'Login successful',
        'token' => $user['token'] // Assuming 'token' key is added to $user by FinalService
    ];

    Flight::json($response);
});





Flight::route('POST /final/investor', function(){
    /** TODO
    * This endpoint is used to add new record to investors and cap-table database tables.
    * Investor contains: first_name, last_name, email and company
    * Cap table fields are share_class_id, share_class_category_id, investor_id and diluted_shares
    * RULE 1: Sum of diluted shares of all investors within given class cannot be higher than authorized assets field
    * for share class given in share_classes table
    * Example: If share_class_id = 1, sum of diluted_shares = 310 and authorized_assets for this share_class = 500
    * It means that investor added to cap table with share_class_id = 1 cannot have more than 190 diluted_shares
    * RULE 2: Email address has to be unique, meaning that two investors cannot have same email address
    * If added successfully output should be the message that investor has been created successfully
    * If error detected appropriate error message should be given as output
    * This endpoint should return output in JSON format
    * Sample output is given in figure 2 (message should be updated according to the result)
    */
    
        $payload = Flight::request()->data->getData();
        $required_fields = ['first_name', 'last_name', 'email', 'company', 'share_class_id', 'share_class_category_id', 'diluted_shares'];
    
        foreach ($required_fields as $field) {
            if (!isset($payload[$field])) {
                Flight::halt(400, "Field $field is required");
            }
        }
    
        try {
            $finalService = Flight::get('final_service');
            $finalService->investor(
                $payload['first_name'],
                $payload['last_name'],
                $payload['email'],
                $payload['company'],
                $payload['share_class_id'],
                $payload['share_class_category_id'],
                $payload['diluted_shares']
            );
            Flight::json(["message" => "Investor has been created successfully"]);
        } catch (Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    });
    
    Flight::route('GET /final/share_classes', function(){
        $share = Flight::get('final_service')->share_classes();
    header('Content-Type: application/json');
    Flight::json($share, 200);
    });
    
    Flight::route('GET /final/share_class_categories', function(){
        $share = Flight::get('final_service')->share_class_categories();
    header('Content-Type: application/json');
    Flight::json($share, 200);
    });




?>
