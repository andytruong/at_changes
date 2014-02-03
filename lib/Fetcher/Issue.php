<?php
namespace Drupal\changes\Fetcher;

class Issue {
    /**
     * @var int
     */
    private $id;

    public function __construct($id) {
        $this->id = (int) $id;
    }

    /**
     * @return \Drupal\changes\Data\Issue
     */
    public function fetch() {
        $doc = new \DOMDocument();
        @$doc->loadHTML($this->fetchHTML());
        $converter = new IssueConverter(new \DOMXPath($doc));
        return $converter->convert();
    }

    private function fetchHTML() {
        $url = sprintf("https://drupal.org/node/%d", $this->id);
        $tmp = variable_get('file_temporary_path') . '/drupalorg.' . $this->id . '.html';
        if (is_file($tmp)) {
            $output = file_get_contents($tmp);
            if (!empty($output)) {
                return $output;
            }
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
          CURLOPT_RETURNTRANSFER => true,     // return web page
          CURLOPT_HEADER         => false,    // don't return headers
          CURLOPT_FOLLOWLOCATION => true,     // follow redirects
          CURLOPT_ENCODING       => "",       // handle all encodings
          CURLOPT_USERAGENT      => "Drupal", // who am i
          CURLOPT_AUTOREFERER    => true,     // set referer on redirect
          CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
          CURLOPT_TIMEOUT        => 120,      // timeout on response
          CURLOPT_MAXREDIRS      => 3,        // stop after 10 redirects
          CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
        ));

        $content = curl_exec($ch);
        $err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);
        curl_close($ch);

        if ($header['http_code'] == 200) {
          file_put_contents($tmp, $content);
          return $content;
        }

        if (!in_array($header['http_code'], array(403, 404))) {
          print_r(array($err, $errmsg, $header));
          exit;
        }
    }
}
