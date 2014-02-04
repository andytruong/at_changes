<?php

namespace Drupal\changes;

class Importer
{

    /**
     * @var Fetcher\Git
     */
    private $git;

    /**
     * @var int
     */
    private $limit = 0;

    public function __construct($git)
    {
        $this->git = $git;
    }

    public function setLimit($limit) {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param array $commit
     * @return \Drupal\changes\Data\Commit
     */
    private function getCommitObject($commit)
    {
        return new Data\Commit($commit);
    }

    /**
     * @param int $source_nid
     * @return Data\Issue
     */
    private function getIssueObject($source_nid)
    {
        $issue_fetcher = new Fetcher\Issue($source_nid);
        return $issue_fetcher->fetch();
    }

    public function import()
    {
        $counter = 0;

        foreach ($this->git->getD8Revisions() as $commit) {
            if ($this->limit && $counter++ >= $this->limit) {
                break;
            }

            // Somecase, author field is empty
            $commit['author'] = !empty($commit['author']) ? $commit['author'] : 'Dries Buytaert <dries@buytaert.net>';

            $commit = $this->getCommitObject($commit);
            $c_node = $commit->saveAsNode();
            function_exists('drush_print_r') && drush_print_r("[Commit #{$c_node->nid}] {$c_node->title}");

            if ($issue_nid = $commit->getIssueNID()) {
                $issue = $this->getIssueObject($commit->getIssueNID());
                $i_node = $issue->saveAsNode();

                function_exists('drush_print_r') && drush_print_r(" > [Issue #{$i_node->nid}] {$i_node->title}");
            }
        }
    }

}
