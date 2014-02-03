<?php

namespace Drupal\changes\Fetcher;

class IssueConverter
{

    private $domxpath;

    public function __construct(\DOMXPath $domxpath)
    {
        $this->domxpath = $domxpath;
    }

    private function getMapping()
    {
        return array(
          'title' => array('#page-subtitle', 'getElementValue'),
          'id' => array('div.node-project-issue', 'getElementId'),
          'date' => array('.node-project-issue .submitted time', 'getElementDate'),
          'body' => array('div.node-project-issue div.content .field-name-body .field-item', 'getElementInnerHTML'),
          'component' => array('#block-project-issue-issue-metadata .field-name-field-issue-component .field-item', 'getElementValue'),
          'tags' => array('#block-project-issue-issue-metadata .field-type-taxonomy-term-reference .field-items .field-item a', 'getElementValue'),
          'changeRecordsIds' => array('.view-change-records .view-content .item-list ul li a', 'getElementNID'),
          'relatedIssueIds' => array('.view-project-issue-issue-relations .view-content .item-list ul li a', 'getElementNID'),
        );
    }

    /**
     * @return \Drupal\changes\Data\Issue
     */
    public function convert()
    {
        $data = array();

        foreach ($this->getMapping() as $property => $def) {
            list($query, $callback) = $def;

            $items = $this->domxpath->query(changes_css_to_xpath($query));
            for ($i = 0; $i < $items->length; ++$i) {
                $data[$property][$i] = $this->{$callback}($items->item($i));
            }

            if ($items->length === 1) {
                $data[$property] = reset($data[$property]);
            }
        }

        return new \Drupal\changes\Data\Issue($data);
    }

    private function getElementValue(\DOMNode $item)
    {
        return $item->nodeValue;
    }

    private function getElementAttribute(\DOMNode $item, $name)
    {
        if ($item->hasAttributes()) {
            return $item->attributes->getNamedItem($name)->nodeValue;
        }
    }

    private function getElementId(\DOMNode $item)
    {
        if ($item->hasAttributes()) {
            return (int) str_replace('node-', '', $this->getElementAttribute($item, 'id'));
        }
    }

    private function getElementInnerHTML(\DOMNode $item)
    {
        $html = '';

        foreach ($item->childNodes as $child) {
            $html .= $item->ownerDocument->saveHTML($child);
        }

        return $html;
    }

    private function getElementNID(\DOMNode $item)
    {
        return (int) str_replace('/node/', '', $this->getElementAttribute($item, 'href'));
    }

    private function getElementDate(\DOMNode $item) {
        # August 18, 2013 at 10:42pm
        $date = \DateTime::createFromFormat('F d, Y \a\t h:ia', $item->nodeValue);
        return $date->getTimestamp();
    }

}
