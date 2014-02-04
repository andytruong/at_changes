<?php

namespace Drupal\changes\Data;

class Issue
{

    private $title;
    private $issue_id;

    /**
     * @var \DateTime
     */
    private $date;
    private $body;
    private $component = array();
    private $tags = array();
    private $changeRecordsIds = array();
    private $relatedIssueIds = array();

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            switch ($k) {
                case 'id':
                    $this->issue_id = (int)$v;
                    break;

                case 'component':
                case 'tags':
                case 'changeRecordsIds':
                case 'relatedIssueIds':
                    if (!empty($v)) {
                        $this->{$k} = is_array($v) ? $v : array($v);
                    }
                    break;

                case 'body':
                    $this->body = array('value' => $v, 'format' => 'full_html');
                    break;

                default:
                    $this->{$k} = $v;
                    break;
            }
        }
    }

    public function saveAsNode() {
        $stub_node = new \Drupal\changes\Stub\Node();
        $stub_taxo = new \Drupal\changes\Stub\Taxonomy();

        $f_nid = \CHANGES::FIELD_ISSUE_NID;
        $f_component = \CHANGES::FIELD_ISSUE_COMPONENT;
        $f_tags = \CHANGES::FIELD_ISSUE_TAGS;
        $f_related = \CHANGES::FIELD_ISSUE_RELATED;
        $f_change = \CHANGES::FIELD_ISSUE_CHANGE_RECORDS;

        $i_node = $stub_node->nodeIssue($this->issue_id);
        $i_node->status = 1;
        $i_node->title = $this->title;
        $i_node->created = $i_node->changed = $this->date;
        $i_node->body['und'][0] = $this->body;
        $i_node->uid = 1;
        $i_node->{$f_nid}['und'][0]['value'] = $this->issue_id;

        $i_node->{$f_component} = array();
        foreach ($stub_taxo->terms($this->component, $f_component) as $term) {
            $i_node->{$f_component}['und'][] = array('tid' => $term->tid);
        }

        $i_node->{$f_tags} = array();
        foreach ($stub_taxo->terms($this->tags, $f_tags) as $term) {
            $i_node->{$f_tags}['und'][] = array('tid' => $term->tid);
        }

        foreach ($this->relatedIssueIds as $i => $nid) {
            if ($r_node = $stub_node->nodeIssue($nid)) {
                $i_node->{$f_related}['und'][$i]['target_id'] = $r_node->nid;
            }
        }

        $i_node->{$f_change} = array();
        foreach ($this->changeRecordsIds as $nid) {
            if ($r_node = $stub_node->nodeChangeNotice($nid)) {
                $i_node->{$f_change}['und'][]['target_id'] = $r_node->nid;
            }
        }

        node_save($i_node);

        return $i_node;
    }
}
