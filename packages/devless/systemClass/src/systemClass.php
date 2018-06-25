<?php
use App\Helpers\ActionClass;
use App\Helpers\DataStore as DS;
use App\Helpers\DevlessHelper as DVH;
use App\Helpers\Helper;
use App\Http\Controllers\ServiceController as service;

/**
 * Created by Devless.
 * User: eddymens
 * Date Created: 23rd of September 2016 09:01:20 AM
 * @Service: event
 * @Version: 1.0
 */

//Action method for serviceName
class devless
{
    public $serviceName = 'dvauth';
    private $auth;

    public function __construct()
    {
        $this->auth = new DVH();
    }

    /**
     * Sample DevLess Method
     * @ACL public
     */
    public function hello()
    {
        return 'Hello World!';
    }

    /**
     * Signup new users  `->beforeCreating()->run('devless','signUp', [$email = "team@devless.io",$password = "pass",$username = null,$phone_number = "020198475",$first_name = "John",$last_name = "Doe",$remember_token = null,$role = 5,$extraParams = null])->storeAs($output)->stopAndOutput(1000, "Created Profile Successfully",$output)`
     *
     * @param $email
     * @param $password
     * @param $username
     * @param $phone_number
     * @param $first_name
     * @param $last_name
     * @param $remember_token
     * @param $role
     * @param $extraParams []
     *
     * @return array
     * @ACL public
     */
    public function signUp(
        $email = null,
        $password = null,
        $username = null,
        $phone_number = null,
        $first_name = null,
        $last_name = null,
        $remember_token = null,
        $role = null,
        $extraParams = null
    ) {
        $payload = get_defined_vars();

        $payload = array_slice($payload, 0, 8);
        $payload = self::getSetParams($payload);
        $auth = $this->auth;
        $output = $auth->signup($payload);
        $extProfile = [];
        if ($extraParams && \Schema::hasTable('devless_user_profile')) {
            $extraParams[]['users_id'] = $extraParams[]['devless_user_id'] = $output['profile']->id;
            $extProfile = $this->addExtraUserDetails($extraParams);
        }
        return (array) $output + $extProfile;
    }

    /**
     * Activate password recovery `->beforeCreating()->run('devless','generatePasswordRecoveryToken', [$userId])->storeAs($output)->stopAndOutput(1000, "recovery token". $output)`
     *
     * @param $token
     * @param $newPassword
     * @return array
     * @ACL private
     */

    public function generatePasswordRecoveryToken($userId)
    {
        $token = $this->auth->generateRandomAlphanums(24);
        $stored = DS::setDump($token, 'recovery_' . $userId);
        if (!$stored) {
            $stored = DS::updateDump($token, 'recovery_', $userId);
        }
        if (!$stored) {
            return false;
        }
        return $token;
    }

    /**
     * Recover users password `->beforeCreating()->run('devless','resetPassword', [$token = '', $newPassword = ''])->storeAs($output)->stopAndOutput(1000, "password reset", $output)`
     *
     * @param $token
     * @param $newPassword
     * @return array
     * @ACL public
     */

    public function resetPassword($token, $newPassword)
    {
        $userIDMeta = DS::getDump($token);
        if (!$userIDMeta) {return false;}
        $explosion = explode('_', $userIDMeta);
        $userId = $explosion[1];
        $output = $this->updateUserProfile($userId, '', $newPassword, '', '', '', '', '', '');
        if ($output) {DS::destroyDump($token);}
        return $output;
    }
    /**
     * login users  `->beforeCreating()->run('devless','login', [$username = null, $email = "team@devless.io", $phone_number = null, $password = "pass"])->storeAs($output)->stopAndOutput(1000, "login user Successfully", $output)`
     *
     * @param $username
     * @param $email
     * @param $phone_number
     * @param $password
     *
     * @return array
     * @ACL public
     */
    public function login($username = null, $email = null, $phone_number = null, $password = null)
    {
        $payload = get_defined_vars();
        $payload = self::getSetParams($payload);

        $auth = $this->auth;

        $output = $auth->login($payload);
        return $output;
    }

    /**
     * get user profile
     * @ACL public
     * @return array
     */
    public function profile()
    {
        $auth = $this->auth;

        $profile = $auth->get_profile();

        return $profile;
    }

    /**
     * logout
     * @ACL public
     */
    public function logout()
    {
        $auth = $this->auth;
        $logState = $auth->logOut();

        return $logState;
    }

    /**
     * method for handling user login
     * @ACL public
     */
    public function updateProfile(
        $email = null,
        $password = null,
        $username = null,
        $phone_number = null,
        $first_name = null,
        $last_name = null,
        $remember_token = null,
        $extraParams = null
    ) {
        $payload = get_defined_vars();
        $payload = array_slice($payload, 0, 7);

        $payload = self::getSetParams($payload);
        $auth = $this->auth;

        $output = $auth->update_profile($payload);
        if ($extraParams) {
            $extraParams[]['users_id'] = $output->id;
        }
        $extraOutput = [];
        if (\Schema::hasTable('devless_user_profile')) {
            $extraOutput = $this->editExtraUserDetails($extraParams);
        }
        return (array) $output + (array) $extraOutput;
    }

    /**
     * reset user account password
     * @ACL public
     */
    public function reset_users_password($token)
    {
        $auth = $this->auth;
        $state = $auth->reset_users_password($token);
        return $state;
    }

    /**
     * verify newly registered emails
     * @ACL public
     */
    public function verify_users_email($token)
    {
        $user_id = DS::getDump($token . '_' . $user_id);
        $status = (\DB::table('users')->where('id', $user_id)->update(['status' => 1])) ? true : false;
        return $status;
    }
    private static function getSetParams($payload)
    {
        foreach ($payload as $key => $value) {
            if ($value == null) {
                unset($payload[$key]);
            }
        }
        return $payload;
    }

    /**
     * add data to a service `->import('devless')->beforeCreating()->addData('service_name','table_name',["name"=>"mike"])->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param $serviceName
     * @param $table
     * @param $fields
     * @return mixed
     * @ACL private
     */
    public function addData($serviceName, $table, $data)
    {
        $service = new service();
        $data = (isset($data[0])) ? $data : [$data];
        $output = DS::service($serviceName, $table, $service)->addData($data);
        return $output;
    }

    /**
     * Get data from a service table `->import('devless')->beforeCreating()->queryData('service_name','table_name',["where"=>["id,1"]])->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param $serviceName
     * @param $table
     * @return mixed
     * @ACL private
     */
    public function queryData($serviceName, $table, $queryParams = null, $getRelated = true)
    {
        $service = new service();

        $queryBuilder = DS::service($serviceName, $table, $service);
        if ($queryParams) {
            foreach ($queryParams as $eachParamName => $eachParamArgs) {
                if (is_array($eachParamArgs)) {
                    foreach ($eachParamArgs as $indiParamArgs) {
                        $queryBuilder->paramsBuilder($eachParamName, $indiParamArgs);
                    }
                } else {
                    $queryBuilder->paramsBuilder($eachParamName, $eachParamArgs);
                }
            }
        }
        if ($getRelated) {
            $output = $queryBuilder->related('*')->queryData();
        } else {
            $output = $queryBuilder->queryData();
        }
        if (isset($output['payload']['results'])) {
            return $output['payload']['results'];
        }
        return $output;
    }

    /**
     * Get data from a service table `->import('devless')->beforeCreating()->getData('service_name','table_name',["where"=>["id,1"]])->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param $serviceName
     * @param $table
     * @return mixed
     * @ACL private
     */
    public function getData($serviceName, $table, $queryParams = null, $getRelated = true)
    {
        return $this->queryData($serviceName, $table, $queryParams, $getRelated);
    }

    /**
     * update data in a service table `->import('devless')->beforeCreating()->updateData('service_name','table_name', "id", 1, ["name"=>"mike"])->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param $serviceName
     * @param $table
     * @param $id
     * @return mixed
     * @ACL private
     */
    public function updateData($serviceName, $table, $whereKey, $whereValue, $data)
    {
        $service = new service();
        $output = DS::service($serviceName, $table, $service)->where($whereKey, $whereValue)->update($data);
        return $output;
    }

    public function force_updateData($serviceName, $table, $whereKey, $whereValue, $data)
    {
        $output = \DB::table($serviceName . '_' . $table)->where($whereKey, $whereValue)->update($data);
        return ($output)? ['status_code'=>619, 'message'=>'Table was updated successfully','payload'=>[]]:
                ['status_code'=>620, 'message'=>'Table could not be created','payload'=>[]];
    }
    /**
     * delete record from a service table `->import('devless')->beforeCreating()->deleteData('test','sample', 1)->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param $serviceName
     * @param $table
     * @param $id
     * @return mixed
     * @ACL private
     */
    public function deleteData($serviceName, $table, $id, $key = 'id')
    {
        $service = new service();
        $output = DS::service($serviceName, $table, $service)->where($key, $id)->delete();
        return $output;
    }

    /**
     * get user profile by id `->import('devless')->beforeCreating()->getUserProfile(2)->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param $id
     * @param $key
     * @return array
     * @ACL private
     */
    public function getUserProfile($input, $key = 'id')
    {
        (empty($input)) ? Helper::interrupt(641) : false;
        if (is_array($input)) {
            $id = $input['id'];
        } else {
            $id = $input;
        }
        $profile = DB::table('users')->where($key, $id)->get();
        if ($profile) {
            $userProfile = (array) $profile[0];
            $extraDetails = $this->getExtraUserDetails($userProfile['id']);
            $completeUserProfile = $userProfile + $extraDetails;
            return $completeUserProfile;
        }
        return [];
    }

    /**
     * get user profile using a field `->import('devless')->beforeCreating()->getUserWhere("username", "foo")->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param $key
     * @param $value
     * @return array
     * @ACL private
     */
    public function getUserWhere($key, $value)
    {
        return $this->getUserProfile($value, $key);
    }

    /**
     * Search for users users were the input matches either username, phone number, first name , last name or emails ->import('devless')-> beforeQuerying()->searchUserProfile("3284324343")>storeAs($users)->stopAndOutput(1000, 'list of users',$users)`
     * @param $input
     * @return array
     * @ACL private
     */

    public function searchUserProfile($input)
    {
        return \DB::table('users')
            ->where('status', 1)
            ->where(function ($query) use ($input) {
                $query->where('email', $input)
                    ->orWhere('username', $input)
                    ->orWhere('phone_number', $input)
                    ->orWhere('first_name', $input)
                    ->orWhere('last_name', $input);
            })->get();
    }

    /**
     * Deactivate user account `->import('devless')->beforeCreating()->deactivateUserAccount("username", "foo")->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param $key
     * @param $value
     * @return array
     * @ACL private
     */
    public function deactivateUserAccount($value, $key = 'id')
    {
        return $this->changeUserStatus(0, $value, $key);
    }

    /**
     * Activate User Account`->import('devless')->beforeCreating()->activateUserAccount("username", "foo")->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param $key
     * @param $value
     * @return array
     * @ACL private
     */
    public function activateUserAccount($value, $key = 'id')
    {
        return $this->changeUserStatus(1, $value, $key);
    }

    /**
     * Toggle User Account Status`->import('devless')->beforeCreating()->toggleUserAccountState(0, "username", "foo")->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param bol $state
     * @param $key
     * @param $value
     * @return array
     * @ACL private
     */
    public function toggleUserAccountState($value, $key)
    {
        $user = $this->getUserWhere($key, $value);
        if (isset($user['status'])) {return [];}
        if ($user['status'] == 0) {return $this->activateUserAccount($value, $key);}
        return $this->deativateUserAccount($value, $key);
    }

    /**
     * changeUserStatus`->import('devless')->beforeCreating()->changeUserStatus(0, "username", "foo")->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param bol $state
     * @param $key
     * @param $value
     * @return array
     * @ACL private
     */
    public function changeUserStatus($state, $value, $key = 'id')
    {
        $user = $this->getUserWhere($key, $value);

        if (!isset($user['id'])) {return false;}

        return $this->updateUserProfile(
            $user['id'],
            $email = '',
            $password = '',
            $username = '',
            $phone_number = '',
            $first_name = '',
            $last_name = '',
            $remember_token = '',
            $status = $state
        );
    }

    /**
     * Get all users `->import('devless')->beforeCreating()->getAllUsers(2)->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @return array
     * @ACL private
     */
    public function getAllUsers()
    {
        $profile = DB::table('users')->select(
            [
                "users.id", "username", "email", "first_name", "last_name", "phone_number", "status",
            ]
        )->get();

        if (Schema::hasTable('devless_user_profile')) {
            $extProfile = DB::table('devless_user_profile')->get();
            foreach ($extProfile as $index => $fields) {
                if ($profileIndex = collect($profile)->where('id', $extProfile[$index]->users_id)->keys()) {
                    foreach ($fields as $key => $value) {
                        if ($key == 'id') {
                            continue;
                        }
                        $profile[$profileIndex[0]]->$key = $value;
                    }
                }
            }
        }

        return $profile;
    }

    /**
     * Delete a users profile `->import('devless')->beforeCreating()->deleteUserProfile(9)->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @return bool
     * @ACL private
     */
    public function deleteUserProfile($id)
    {
        return (\DB::table('users')->where('id', $id)->delete()) ? true : false;
    }

    /**
     * Update a users profile `->import('devless')->beforeCreating()->updateUserProfile($id=1,$email = '',$password = '',$username = 'eddymens',$phone_number = '',$first_name = '',$last_name = '')->storeAs($output)->stopAndOutput(1000, "output", $output)`
     * @param $email
     * @param $password
     * @param $username
     * @param $phone_number
     * @param $first_name
     * @param $last_name
     * @param $remember_token
     * @param $role
     * @return bool
     * @ACL private
     */
    public function updateUserProfile(
        $id,
        $email = '',
        $password = '',
        $username = '',
        $phone_number = '',
        $first_name = '',
        $last_name = '',
        $remember_token = '',
        $status = ''
    ) {
        $profileUpdate = array_filter(
            get_defined_vars(),
            function ($value) {
                return $value !== '';
            }
        );
        if (isset($profileUpdate['password'])) {
            $profileUpdate['password'] = Helper::password_hash($profileUpdate['password']);
        }
        unset($profileUpdate['id']);
        return (DB::table('users')->where('id', $id)->update($profileUpdate)) ? true : false;
    }

    /**
     * Login users with username and password
     * @param $username
     * @param $password
     * @return array
     * @ACL public
     */
    public function usernameLogin($username, $password)
    {
        return $this->login($username, null, null, $password);
    }

    /**
     * Login users with email and password
     * @param $email
     * @param $password
     * @return array
     * @ACL public
     */
    public function emailLogin($email, $password)
    {
        return $this->login(null, $email, null, $password);
    }

    /**
     * Login users with phone number and password
     * @param $phone_number
     * @param $password
     * @return array
     * @ACL public
     */
    public function phoneNumberLogin($phone_number, $password)
    {
        return $this->login(null, null, $phone_number, $password);
    }

    /**
     * Lists and explains what each method is used for
     * @return array
     * @ACL public
     */
    public function help()
    {
        $serviceInstance = $this;
        $actionClass = new ActionClass();
        return $actionClass->help($serviceInstance, $methodToGetDocsFor = null);
    }

    public function getExtraUserDetails($id)
    {
        $service = new service();
        $output = DS::service('devless', 'user_profile', $service)->where('users_id', $id)->getData();
        if (!isset($output['payload']['results'])) {return [];}
        $output = $output['payload']['results'];
        if (!isset($output[0])) {
            return [];
        }
        $newOutput = (array) $output[0];
        unset($newOutput['id'], $newOutput['devless_user_id'], $newOutput['users_id']);
        return $newOutput;
    }

    public function addExtraUserDetails($extraDetails)
    {
        $service = new service();
        $flattendDetails = [];

        for ($i = 0; $i < count($extraDetails); $i++) {
            $key = array_keys($extraDetails[$i]);
            $value = array_values($extraDetails[$i]);
            $flattendDetails[$key[0]] = $value[0];
        }
        $output = DS::service('devless', 'user_profile', $service)->addData([$flattendDetails]);
        if ($output['status_code'] != 609) {

            DB::table('users')->where('id', $flattendDetails['users_id'])->delete();
            return Helper::interrupt(644, $output['message']);

        }
        unset($flattendDetails['users_id'], $flattendDetails['devless_user_id']);
        return $flattendDetails;
    }

    public function editExtraUserDetails($extraDetails)
    {
        $service = new service();
        $flattendDetails = [];

        for ($i = 0; $i < count($extraDetails); $i++) {
            $key = array_keys($extraDetails[$i]);
            $value = array_values($extraDetails[$i]);
            $flattendDetails[$key[0]] = $value[0];
        }

        if (!isset($flattendDetails['users_id'])) {
            return [];
        }
        $id = $flattendDetails['users_id'];
        unset($flattendDetails['users_id']);

        $output = DS::service('devless', 'user_profile', $service)->where('users_id', $id)->update($flattendDetails);
        if ($output['status_code'] != 619) {
            return Helper::interrupt(629, $output['message']);
        }
        return DS::service('devless', 'user_profile', $service)->where('users_id', $id)->getData()['payload']['results'][0];
    }
}
