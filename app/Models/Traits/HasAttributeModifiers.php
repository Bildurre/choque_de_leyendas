<?php

namespace App\Models\Traits;

trait HasAttributeModifiers
{
  /**
   * Default attribute modifier fields
   *
   * @var array
   */
  protected $attributeModifierFields = [
    'agility_modifier',
    'mental_modifier',
    'will_modifier',
    'strength_modifier',
    'armor_modifier'
  ];

  /**
   * Get the attribute modifier fields
   *
   * @return array
   */
  public function getAttributeModifierFields(): array
  {
    return $this->attributeModifierFields ?? [
      'agility_modifier',
      'mental_modifier',
      'will_modifier',
      'strength_modifier',
      'armor_modifier'
    ];
  }

  /**
   * Set the attribute modifier fields
   *
   * @param array $fields
   * @return $this
   */
  public function setAttributeModifierFields(array $fields): self
  {
    $this->attributeModifierFields = $fields;
    return $this;
  }

  /**
   * Calculate the number of modified attributes
   *
   * @return int
   */
  public function getModifiedAttributesCount(): int
  {
    $count = 0;
    
    foreach ($this->getAttributeModifierFields() as $field) {
      if (($this->{$field} ?? 0) != 0) {
        $count++;
      }
    }
    
    return $count;
  }
  
  /**
   * Calculate the sum of all modifiers
   *
   * @return int
   */
  public function getTotalModifiersSum(): int
  {
    $sum = 0;
    
    foreach ($this->getAttributeModifierFields() as $field) {
      $sum += ($this->{$field} ?? 0);
    }
    
    return $sum;
  }

  /**
   * Get the values of all modifier fields
   * 
   * @return array
   */
  public function getModifierValues(): array
  {
    $values = [];
    
    foreach ($this->getAttributeModifierFields() as $field) {
      $values[$field] = $this->{$field} ?? 0;
    }
    
    return $values;
  }

  /**
   * Validate attribute modifiers based on configurations
   *
   * @param array $options Validation options
   * @return bool
   */
  public function validateModifiers(array $options = []): bool
  {
    // Get all the values we need for validation
    $modifiedCount = $this->getModifiedAttributesCount();
    $fieldValues = $this->getModifierValues();

    // Validate max modifiable attributes count
    if (isset($options['max_modifiable_attributes']) && $modifiedCount > $options['max_modifiable_attributes']) {
      return false;
    }

    // Validate total sum
    if (isset($options['max_total_sum']) && abs($this->getTotalModifiersSum()) > $options['max_total_sum']) {
      return false;
    }

    // Process attribute_limits - can be a single value or an array of limits
    $attributeLimits = [];
    if (isset($options['attribute_limits'])) {
      if (is_array($options['attribute_limits'])) {
        // If it's already an array with specific limits
        $attributeLimits = $options['attribute_limits'];
      } else {
        // If it's a single value to be applied to all attributes
        $limit = $options['attribute_limits'];
        foreach ($this->getAttributeModifierFields() as $field) {
          $attributeName = str_replace('_modifier', '', $field);
          $attributeLimits[$attributeName] = $limit;
        }
      }
    }

    // Validate per attribute limits
    foreach ($fieldValues as $field => $value) {
      $attributeName = str_replace('_modifier', '', $field);
      if (isset($attributeLimits[$attributeName])) {
        $limit = $attributeLimits[$attributeName];
        
        if (is_array($limit)) {
          // If limit is [min, max]
          if ($value < $limit[0] || $value > $limit[1]) {
            return false;
          }
        } else {
          // If limit is just a max absolute value
          if (abs($value) > $limit) {
            return false;
          }
        }
      }
    }

    return true;
  }
}