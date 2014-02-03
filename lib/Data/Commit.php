<?php

namespace Drupal\changes\Data;

class Commit
{

    /**
     * @var string
     */
    private $author;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $sha1;

    /**
     * @var string
     */
    private $message;

    /**
     * @var int
     */
    private $issue_nid;

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->{$k} = $v;
        }

        $this->message = trim($this->message, '- ');

        // Get issue NID from commit message
        if (preg_match('/#(\d+)/', $this->message, $matches)) {
            if (!empty($matches[1])) {
                $this->issue_nid = $matches[1];
            }
        }
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getData()
    {
        return $this->date;
    }

    public function getSha1()
    {
        return $this->sha1;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getIssueNID()
    {
        return $this->issue_nid;
    }

    public function saveAsNode()
    {
        $stub_node = new \Drupal\changes\Stub\Node();
        $stub_user = new \Drupal\changes\Stub\User();

        $c_node = $stub_node->nodeCommit($this->sha1);
        $author = $stub_user->account($this->author);

        $c_node->status = 1;
        $c_node->created = $c_node->changed = $this->date->getTimestamp();
        $c_node->title = truncate_utf8($this->message, 128);
        $c_node->uid = $author->uid;

        $c_node->{\CHANGES::FIELD_COMMIT_SHA1}['und'][0]['value'] = $this->sha1;
        $c_node->{\CHANGES::FIELD_COMMIT_AUTHOR}['und'][0]['value'] = $this->author;
        $c_node->{\CHANGES::FIELD_COMMIT_MESSAGE}['und'][0]['value'] = $this->message;

        if ($issue_nid = $this->getIssueNID()) {
            $i_node = $stub_node->nodeIssue($issue_nid);
            $c_node->{\CHANGES::FIELD_COMMIT_ISSUE}['und'][0]['target_id'] = $i_node->nid;
        }

        node_save($c_node);

        return $c_node;
    }

}
