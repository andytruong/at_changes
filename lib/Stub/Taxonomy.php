<?php
namespace Drupal\changes\Stub;

class Taxonomy {
    public function vocabulary($machine_name) {
        if ($vocabulary = taxonomy_vocabulary_machine_name_load($machine_name)) {
            return $vocabulary;
        }

        $vocabulary = new \stdClass();
        $vocabulary->name = $machine_name;
        taxonomy_vocabulary_save($vocabulary);
        return $vocabulary;

    }

    public function term($name, $vocabulary_machine_name) {
        if ($term = taxonomy_get_term_by_name($name, $vocabulary_machine_name)) {
            return reset($term);
        }

        if ($vocabulary = taxonomy_vocabulary_machine_name_load($vocabulary_machine_name)) {
            $term = new \stdClass();
            $term->vid = $vocabulary->vid;
            $term->vocabulary_machine_name = $vocabulary_machine_name;
            $term->name = $name;
            $term->weight = 0;

            taxonomy_term_save($term);

            return $term;
        }

        throw new \Exception('Can not find vocabulary: ' . $vocabulary_machine_name);
    }

    public function terms($names, $vocabulary_machine_name) {
        foreach (is_string($names) ? array($names) : $names as $name) {
            $terms[] = $this->term($name, $vocabulary_machine_name);
        }
        return !empty($terms) ? $terms : array();
    }
}
