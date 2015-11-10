<?php

if (!defined('RAPIDLEECH')) {
        require_once ("index.html");
        exit();
}

class easybytez_com extends DownloadClass {
        private $page, $cookie;
        public function Download($link) {
                global $premium_acc;
                $this->cookie = array('lang' => 'english');
                $this->page = $this->GetPage($link, $this->cookie);
                is_present($this->page, "The file you were looking for could not be found");
                is_present($this->page, "The file of the above link no longer exists");

                if ($_REQUEST["premium_acc"] == "on" && ((!empty($_REQUEST["premium_user"]) && !empty($_REQUEST["premium_pass"])) || (!empty($premium_acc["easybytez_com"]["user"]) && !empty($premium_acc["easybytez_com"]["pass"])))) {
                        $this->Login($link);
                } else {
                        $this->FreeDL($link);
                }
        }

        private function FreeDL($link) {
                $page2 = cut_str($this->page, 'Form method="POST" action=', '</form>'); //Cutting page
                $post = array();
                $post['op'] = cut_str($page2, 'name="op" value="', '"');
                $post['usr_login'] = (empty($this->cookie['xfss'])) ? '' : $this->cookie['xfss'];
                $post['id'] = cut_str($page2, 'name="id" value="', '"');
                $post['fname'] = cut_str($page2, 'name="fname" value="', '"');
                $post['referer'] = '';
                $post['method_free'] = cut_str($page2, 'name="method_free" value="', '"');

                $page = $this->GetPage($link, $this->cookie, $post);
                if (preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@', $page, $err)) html_error("Error: ".$err[0]);

                $page2 = cut_str($page, '<form name="F1" method="POST"', '</form>'); //Cutting page
                $post = array();
                $post['op'] = cut_str($page2, 'name="op" value="', '"');
                $post['id'] = cut_str($page2, 'name="id" value="', '"');
                $post['rand'] = cut_str($page2, 'name="rand" value="', '"');
                $post['referer'] = '';
                $post['method_free'] = cut_str($page2, 'name="method_free" value="', '"');
                $post['down_script'] = 1;

                if (!preg_match('@<span id="countdown_str">[^<|>]+<span[^>]*>(\d+)</span>[^<|>]+</span>@i', $page2, $count)) $count = array(1=>60);
                if ($count[1] > 0) $this->CountDown($count[1]);

                $page = $this->GetPage($link, $this->cookie, $post);
                is_present($page, ">Skipped countdown", "Error: Skipped countdown?.");
                if (preg_match('@You can download files up to \d+ [K|M|G]b only.@i', $page, $err)) html_error("Error: ".$err[0]);
                if (!preg_match('@https?://[^/|\r|\n|\"|\'|<|>]+/(?:(?:files)|(?:cgi-bin/dl\.cgi))/[^\r|\n|\"|\'|<|>]+@i', $page, $dlink)) html_error('Error: Download link not found.');

                $FileName = urldecode(basename(parse_url($dlink[0], PHP_URL_PATH)));
                $this->RedirectDownload($dlink[0], $FileName);
        }

        private function PremiumDL($link) {
                $page = $this->GetPage($link, $this->cookie);
                if (!preg_match('@Location: (https?://[^/|\r|\n]+/files/[^\r|\n]+)@i', $page, $dlink)) {
                        $page2 = cut_str($page, '<form name="F1" method="POST"', '</form>'); //Cutting page

                        $post = array();
                        $post['op'] = cut_str($page2, 'name="op" value="', '"');
                        $post['id'] = cut_str($page2, 'name="id" value="', '"');
                        $post['rand'] = cut_str($page2, 'name="rand" value="', '"');
                        $post['referer'] = '';
                        $post['method_premium'] = cut_str($page2, 'name="method_premium" value="', '"');
                        $post['down_direct'] = 1;

                        $page = $this->GetPage($link, $this->cookie, $post);

                        if (!preg_match('@https?://[^/|\r|\n|\"|\'|<|>]+/(?:(?:files)|(?:cgi-bin/dl\.cgi))/[^\r|\n|\"|\'|<|>]+@i', $page, $dlink)) html_error('Error: Download-link not found.');
                }

                $FileName = urldecode(basename(parse_url($dlink[0], PHP_URL_PATH)));
                $this->RedirectDownload($dlink[0], $FileName);
        }

        private function Login($link) {
                global $premium_acc;
                $lurl='http://www.easybytez.com/login2.html';
                $page2 = $this->GetPage($lurl, $this->cookie);
                $rand = cut_str($page2, '<input type="hidden" name="rand" value="','">');
                $pA = (!empty($_REQUEST["premium_user"]) && !empty($_REQUEST["premium_pass"]) ? true : false);
                $user = ($pA ? $_REQUEST["premium_user"] : $premium_acc["easybytez_com"]["user"]);
                $pass = ($pA ? $_REQUEST["premium_pass"] : $premium_acc["easybytez_com"]["pass"]);

                if (empty($user) || empty($pass)) html_error("Login Failed: User or Password is empty. Please check login data.", 0);
                $post = array();
                $post['login'] = urlencode($user);
                $post['password'] = urlencode($pass);
                $post['rand'] = $rand;
                $post['op'] = "login2";
                $post['redirect'] = "";

                $purl = 'http://easybytez.com/';
                $page = $this->GetPage($purl, $this->cookie, $post, $purl);
                is_present($page, "Incorrect Login or Password", "Login Failed: User/Password incorrect.");

                $this->cookie = GetCookiesArr($page);
                if (empty($this->cookie['xfss'])) html_error("Login Error: Cannot find session cookie.");
                $this->cookie['lang'] = 'english';

                $page = $this->GetPage("$purl?op=my_account", $this->cookie, 0, $purl);
                if (stripos($page, '/?op=logout') === false && stripos($page, '/logout') === false) html_error('Login Error.');

                if (stripos($page, "Premium account expire") === false) {
                        $this->changeMesg(lang(300)."<br /><b>Account isn\\\'t premium</b><br />Using it as member.");
                        return $this->FreeDL($link);
                } else return $this->PremiumDL($link);
        }
}

// [19-6-2012]  Written by Th3-822. (XFS... XFS everywhere. :D)
// [02-10-2014] Fixed by Tblogger . (looks like this site has bug in login :-P)

?>
