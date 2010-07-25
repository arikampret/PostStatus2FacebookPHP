<?php
  /*
  Copyright (C) 2010 Sony Arianto Kurniawan <sony@sony-ak.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see http://www.gnu.org/licenses/.

  ---------------------------------------------------------------------

  Script Name: post_status_to_facebook.php
  Last Update: July 25, 2010
  Location of Last Update: Bangalore, India
  */

  // facebook credentials (change to appropriate value)
  $username = "facebook_username";
  $password = "facebook_password";

  // do login to facebook
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, "https://login.facebook.com/login.php?m&next=http://m.facebook.com/home.php");
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_POSTFIELDS, "email=" . $username . "&pass=" . $password . "&login=Log In");
  curl_setopt($curl, CURLOPT_ENCODING, "");
  curl_setopt($curl, CURLOPT_COOKIEJAR, getcwd() . '/cookies_facebook.cookie');
  $curlData = curl_exec($curl);
  curl_close($curl);

  // do get post url
  $urlPost = substr($curlData, strpos($curlData, "action=\"/a/home") + 8);
  $urlPost = substr($urlPost, 0, strpos($urlPost, "\""));
  $urlPost = "http://m.facebook.com" . $urlPost;

  // do get some parameters for updating the status
  $fbDtsg = substr($curlData, strpos($curlData, "name=\"fb_dtsg\""));
  $fbDtsg = substr($fbDtsg, strpos($fbDtsg, "value=") + 7);
  $fbDtsg = substr($fbDtsg, 0, strpos($fbDtsg, "\""));

  $postFormId = substr($curlData, strpos($curlData, "name=\"post_form_id\""));
  $postFormId = substr($postFormId, strpos($postFormId, "value=") + 7);
  $postFormId = substr($postFormId, 0, strpos($postFormId, "\""));

  // do update facebook status
  $statusMessage = "Hey, I can update my facebook status from PHP (July 25, 2010)";

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $urlPost);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, "fb_dtsg=" . $fbDtsg . "&post_form_id=" . $postFormId . "&status=" . $statusMessage . "&update=Update Status");
  curl_setopt($curl, CURLOPT_ENCODING, "");
  curl_setopt($curl, CURLOPT_COOKIEFILE, getcwd() . '/cookies_facebook.cookie');
  curl_setopt($curl, CURLOPT_COOKIEJAR, getcwd() . '/cookies_facebook.cookie');
  $curlData = curl_exec($curl);
  curl_close($curl);

  // display end facebook page after posting new status message
  echo $curlData;