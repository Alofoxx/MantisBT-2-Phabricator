<?php
/**
 * Phabricator user functions.
 *
 * @see github <https://github.com/Alofoxx/MantisBT-2-Phabricator>
 * @author Alofoxx <https://github.com/Alofoxx>
 * @license Apache License 2.0 <http://www.apache.org/licenses/>
 */

//root to phabricator folder in installed phab
$root     = "/var/www/phab/phabricator";
require_once $root.'/scripts/__init_script__.php';

/* Debuging and testing
error_reporting(E_ALL);
ini_set("display_errors", true);
print "<pre>";


$admin = "admin";

$user['realname'] = "Testy Tester2";
$user['email']  = "test2@test.com";
$user['username'] = "test2";
$user['title'] = "Mr test2";
$user['enabled'] = 0;


$test = getOrAddUser($user, $admin);
print_r($test);
*/

/**
 * Gets or creates a phid for the user.
 *
 * Will try to find the user via username first, then by email. If found it will
 * not edit phab user, and return existing phid, username, email from phab.
 *
 * If user does not exist then it creates one, and also sets the title and active.
 *
 * @author Alofoxx
 * @param array $user User to get account for
 * @param string $admin username of Phab admin [1st user account]
 * @param bool $sendEmail - future implementation
 * @param array $Email - future implementation
 * @return array
 */
function getOrAddUser($user, $admin = "")
{
    if (!is_array($user) || $admin == "") {
        //cant do anything.
        return array(
            "error" => true,
            "errorMessage" => "User array or admin username was not passed to getOrAddUser()"
        );
    }

    $username = $user['username'];
    $address  = $user['email'];
    $realname = $user['realname'];


    //verify admin
    $admin = id(new PhabricatorUser())->loadOneWhere(
        'username = %s', $admin);
    if (!$admin) {
        //cant do anything.
        return array(
            "error" => true,
            "errorMessage" => 'Admin user must be the username of a valid '.
            'Phabricator account, used to send the new user a welcome email.'
        );
    }

    //check if user exists
    //check by username first
    $existing_user = id(new PhabricatorUser())->loadOneWhere(
        'username = %s', $username);

    if (!$existing_user) {
        //username not found
        //check by email
        $email = id(new PhabricatorUserEmail())->loadOneWhere(
            'address = %s', $address);
        if ($email) {
            //load user from found email match
            $existing_user = id(new PhabricatorUser())->loadOneWhere(
                'phid = %s', $email->getUserPHID());
        }
    }

    //return our phab user details if username or email was found.
    if ($existing_user) {
        $results['error']    = false;
        $results['phid']     = $existing_user->getPHID();
        $results['username'] = $existing_user->getUsername();
        $results['realname'] = $existing_user->getRealName();
        return $results;
    }

    //no user found - so create new user.

    $new_user = new PhabricatorUser();
    $new_user->setUsername($username);
    $new_user->setRealname($realname);
    $new_user->setIsApproved(1);

    //set disabled
    if(!$user['enabled']){
        $new_user->setIsDisabled(1);
    }

    //add email
    $email_object = id(new PhabricatorUserEmail())
        ->setAddress($address)
        ->setIsVerified(1);

    //create the user
    id(new PhabricatorUserEditor())
        ->setActor($admin)
        ->createNewUser($new_user, $email_object);

    //set title if not empty
    if($user['title'] != ""){
        $result = setUserTitle($new_user, $user['title']);
        if($result['error']){
            return $result;
        }
    }

    if ($new_user) {
        $results['error']    = false;
        $results['phid']     = $new_user->getPHID();
        $results['username'] = $new_user->getUsername();
        $results['realname'] = $new_user->getRealName();
        return $results;
    } else {
        return array(
            "error" => true,
            "errorMessage" => 'Something prevented the user: '.$username.
            ' from having an account created.'
        );
    }
    //send email
    //$new_user->sendWelcomeEmail($admin);
}

/**
 * Update the Title on a user profile for a Phabricator user.
 * 
 * There was no simple proccess for this even in conduit.
 * so I made my own tool that does it mostly like the actual
 * edit profile view.
 *
 * @author Alofoxx
 */
function setUserTitle(PhabricatorUser $phabUser, $title)
{
    //generate profile edit form.
    $field_list = PhabricatorCustomField::getObjectFields(
            $phabUser, PhabricatorCustomField::ROLE_EDIT);
    $field_list
        ->setViewer($phabUser)
        ->readFieldsFromStorage($phabUser);

    //get existing data so you dont overwrite it - or set defaults if null.
    $profile = $phabUser->loadUserProfile();

    $realname = $phabUser->getRealName();
    if (is_null($realname)) {
        $realname = "";
    }
    $blurb = $profile->getBlurb();
    if (is_null($blurb)) {
        $blurb = "";
    }
    $icon = $profile->getIcon();
    if (is_null($icon)) {
        $icon = "person";
    }

    //set up the request to process
    $request  = id(new AphrontRequest("127.0.0.1", '/'))
        ->setRequestData(array(
        "user:realname" => $realname,
        "user:title" => $title,
        "user:icon" => $icon,
        "user:blurb" => $blurb
        )
    );

    $xactions = $field_list->buildFieldTransactionsFromRequest(
        new PhabricatorUserTransaction(), $request);

    $editor = id(new PhabricatorUserProfileEditor())
        ->setActor($phabUser)
        ->setContentSource(
            PhabricatorContentSource::newFromRequest($request))
        ->setContinueOnNoEffect(true);

    try {
        $editor->applyTransactions($phabUser, $xactions);
    } catch (PhabricatorApplicationTransactionValidationException $ex) {
        $validation_exception = $ex;
        return array(
            "error" => true,
            "errorMessage" => $validation_exception
        );
    }
    return array(
            "error" => false
        );
}
