<?php
if (!defined('RAPIDLEECH')) {
        require_once 'index.html';
        exit;
}

class fshare_vn extends DownloadClass {

        public function Download($link) {
                global $premium_acc;
                
                // Link Verification
                $this->LinkRegExp = '/https?:\/\/(?:www\.)?fshare\.vn\/file\/\w+\/?/im';
                if(!preg_match($this->LinkRegExp, $link, $output)){
                        html_error('Looks Like Your Link Is Invalid');
                }
                 
                // Get English Cookies
                $page = $this->GetPage('https://www.fshare.vn/location/en');
                $engcookies = GetCookiesArr($page);

                // Intialize 

                if (!$_REQUEST['step']) {
                        $this->page = $this->GetPage($link,$engcookies);
                        is_present($this->page, 'Your requested file does not existed.');
                        $this->cookie = GetCookies($page);
                }
                $this->link = $link;
                if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass']) || ($premium_acc['fshare_vn']['user'] && $premium_acc['fshare_vn']['pass']))) {
                        return $this->Premium();
                } elseif ($_REQUEST['step'] == 1) {
                        return $this->DownloadFree();
                } else {
                        return $this->PrepareFree();
                }
        }

        private function Premium() {

                $cookie = $this->login();
                $page = $this->GetPage($this->link, $cookie);
            if(!preg_match('/Location:\s([^\r\n]+)/im', $page,$dl)){
            	$filename = cut_str($page, '<div class="file" title="','">');
            	$csrf = cut_str($page,'fs_csrf: \'','\'');
            	$post = array('speed'=>'speed','fs_csrf'=>$csrf);
            	$page = $this->GetPage('https://www.fshare.vn/download/index',$cookie,$post);
            	$page = $this->Get_Reply($page);
            	$durl = $page['url'];

            }
                if(!$dl){
                	$dlink = $durl;
                } else{
                	$dlink = $dl[1];
                }
                
                $this->RedirectDownload($dlink, $filename, $cookie, 0, $this->link, $filename);
        }

        private function login() {
                global $premium_acc,$engcookies;
              
                $user = ($_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["fshare_vn"] ["user"]);
                $pass = ($_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["fshare_vn"] ["pass"]);
                if (empty($user) || empty($pass)) html_error("Login failed, username or password is empty!");
                
                $url = 'https://www.fshare.vn';
                $page = $this->GetPage($url.'/login',$engcookies);
                $cookies = GetCookiesArr($page);
                $csrf = cut_str($page,'<div style="display:none"><input type="hidden" value="','" name="fs_csrf" /></div>'); 
                $post = array();
                $post['fs_csrf'] = $csrf;
                $post['LoginForm[email]'] = $user;
                $post['LoginForm[password]'] = $pass;
                $post['LoginForm[rememberMe]'] = 0;
                $post['LoginForm[rememberMe]'] = 1;
                $post['yt0'] = 'login';
                $page = $this->GetPage($url.'/login', $cookies, $post, $url.'/login');
                $cookies = GetCookiesArr($page);
                is_present($page, 'Incorrect username or password');

                //check account
                $page = $this->GetPage($url.'/account/infoaccount', $cookies, 0, $url.'/');
                is_notpresent($page, 'Expire Date', 'Account isn\'t Premium?');

                return $cookies;
        }

        private function DownloadFree() {
                html_error('Currently Not Supported');
        }

        private function PrepareFree() {
                html_error('Currently Not Supported');
        }

        private function Get_Reply($page) {
                if (!function_exists('json_decode')) html_error("Error: Please enable JSON in php.");
                $json = substr($page, strpos($page, "\r\n\r\n") + 4);
                $json = substr($json, strpos($json, "{"));
                $json = substr($json, 0, strrpos($json, "}") + 1);
                $rply = json_decode($json, true);
                if (!$rply || (is_array($rply) && count($rply) == 0)) html_error("Error getting json data.");
                return $rply;
        }


}

/* Written By Tblogger [Fshare.vn Premium Download Plugin 05-05-2015]

*/


?>
