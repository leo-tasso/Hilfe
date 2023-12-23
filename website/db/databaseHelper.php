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
}