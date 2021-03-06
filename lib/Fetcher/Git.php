<?php
namespace Drupal\changes\Fetcher;

class Git extends Git_Sebastian {
    public function getD8Revisions($since = 'Sun Mar 20 20:28:12 2011 -0400') {
        //  --reverse
        return $this->getRevisions('git log --no-merges --date-order --format=medium --after="'. $since .'"');
    }

    public function pull($source, $branch) {
        $this->execute(
            "git pull {$source} {$branch} --force --quiet 2>&1",
            $output,
            $return
        );
    }
}
