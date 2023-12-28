<?php
interface DatabaseHelper
{
    public function getHelpPosts($n, $startId, $lat, $long, $radius);
    public function getUserFromId($id);
    public function getProfilePicPathFromId($id);
    public function getHelpPost($id);
    public function getAuthorFromHelpPost($id);
    public function getMaterialFromHelpPost($id);
    public function getSelfProfilePic();
    public function getProfilePic($id);
    public function getNotification();
    public function getSuggestedUsers($n);
    public function isPostSaved($n);
    public function savePost($id, $set);
    public function isParticipating($n);
    public function participatePost($id, $set);
    public function getParticipants($id);
    public function addDescription($users);
    public function followsMe($otherUserId);
    public function followInCommon($otherUserId);
    public function getAddress();
    public function login($email, $password, $remember);
    public function checkbrute($user_id);
    public function login_check();
    public function getFollowing($id);
    public function getFollower($id);
    public function saveToken($user_id, $token);
    public function loginWithToken($token);
    public function checkToken();

}
