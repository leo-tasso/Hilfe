<?php
interface DatabaseHelper{
    public function getHelpPosts($n);
    public function getUserFromId($id);
    public function getProfilePicPathFromId($id);
    public function getAuthorFromHelpPost($id);
    public function getMaterialFromHelpPost($id);
    public function getSelfProfilePic();
    public function getProfilePic($id);
    public function getNotification();
    public function getSuggestedUsers($n);
    public function isPostSaved($n);
    public function savePost($id,$set);
    public function isParticipating($n);
    public function participatePost($id,$set);
    public function getParticipants($id);



}