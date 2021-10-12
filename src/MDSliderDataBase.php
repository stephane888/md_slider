<?php

/**
 * @file
 * Contains \Drupal\md_slider\MDSliderDataBase.
 */

namespace Drupal\md_slider;

class MDSliderDataBase {

  /**
   * @param $table
   * @param $field
   * @return \Drupal\Core\Database\StatementInterface|int|null
   */
  public static function insert($table, $field) {
  	$database = \Drupal::database();
    $return_value = NULL;
    try {
    	$return_value = $database->insert($table)
        ->fields($field)
        ->execute();
    }
    catch (\Exception $e) {
      drupal_set_message(t('db_insert failed. Message = %message, query= %query', array(
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      )), 'error');
    }
    return $return_value;
  }

  public static function update($table, $fields, $conditions) {
  	$database = \Drupal::database();
    try {
    	$count = $database->update($table)
        ->fields($fields);
      foreach ($conditions as $field => $value) {
        $count->condition($field, $value);
      }
      $count->execute();
    }
    catch (\Exception $e) {
      drupal_set_message(t('db_update failed. Message = %message, query= %query', array(
        '%message' => $e->getMessage(),
        '%query' => $e->query_string,
      )), 'error');
    }
    return $count;
  }
  /**
   * @param $table
   * @param array $entry
   */
  public static function load($table, $entry = array()) {
  	$database = \Drupal::database();
  	$select = $database->select($table, 'table_alias');
    $select->fields('table_alias');
    foreach ($entry as $field => $value) {
      $select->condition($field, $value);
    }
    return $select->execute()->fetchAssoc();
  }

  /**
   * @param $table
   * @param array $entry
   * @param null $sort
   * @return
   */
  public static function loadAll($table, $entry = array(), $sort = array()) {
  	$database = \Drupal::database();
  	$select = $database->select($table, 'table_alias');
    $select->fields('table_alias');
    if (count($entry) > 0) {
      foreach ($entry as $field => $value) {
        $select->condition($field, $value);
      }
    }
    if (count($sort) > 0) {
      foreach ($sort as $field => $value) {
        $select->orderBy($field, $value);
      }
    }

    return $select->execute()->fetchAll();
  }

  public static function delete($table, $entry = array()) {
  	$database = \Drupal::database();
  	$delete = $database->select($table);
    if (count($entry) > 0) {
      foreach ($entry as $field => $value) {
        $delete->condition($field, $value);
      }
    }
    return $delete->execute();
  }
}