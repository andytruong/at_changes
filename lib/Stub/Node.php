<?php
namespace Drupal\changes\Stub;

class Node {
    /**
     * @return \EntityFieldQuery
     */
    private function getNodeQuery($bundle, $field, $value) {
        $query = new \EntityFieldQuery();
        $query->entityCondition('entity_type', 'node');
        $query->entityCondition('bundle', $bundle);
        $query->fieldCondition($field, 'value', $value);
        return $query;
    }

    private function getExistingNode($bundle, $field, $value) {
        $query = $this->getNodeQuery($bundle, $field, $value);
        if ($results = $query->execute()) {
            $nid = reset(array_keys(reset($results)));
            if ($node = node_load($nid)) {
                return $node;
            }
        }
    }

    private function initNode($bundle, $field, $value) {
        $node = new \stdClass();
        $node->type = $bundle;
        $node->{$field}['und'][0]['value'] = $value;
        $node->status = 0;
        $node->language = \LANGUAGE_NONE;
        $node->title = "Stub for {$bundle} {$field} = {$value}";
        node_save($node);
        return $node;
    }

    public function nodeCommit($sha1) {
        if ($node = $this->getExistingNode(\CHANGES::TYPE_COMMIT, \CHANGES::FIELD_COMMIT_SHA1, $sha1)) {
            return $node;
        }
        return $this->initNode(\CHANGES::TYPE_COMMIT, \CHANGES::FIELD_COMMIT_SHA1, $sha1);
    }

    public function nodeIssue($source_nid) {
        if ($node = $this->getExistingNode(\CHANGES::TYPE_ISSUE, \CHANGES::FIELD_ISSUE_NID, $source_nid)) {
            return $node;
        }
        return $this->initNode(\CHANGES::TYPE_ISSUE, \CHANGES::FIELD_ISSUE_NID, $source_nid);
    }

    public function nodeChangeNotice($source_nid) {
        if ($node = $this->getExistingNode(\CHANGES::TYPE_CHANGE_NOTICE, \CHANGES::FIELD_ISSUE_NID, $source_nid)) {
            return $node;
        }
        return $this->initNode(\CHANGES::TYPE_ISSUE, \CHANGES::FIELD_ISSUE_NID, $source_nid);
    }
}
