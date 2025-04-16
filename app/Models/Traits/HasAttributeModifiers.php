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
   * Calculate the sum of absolute values of all modifiers
   *
   * @return int
   */
  public function getAbsoluteModifiersSum(): int
  {
    $sum = 0;
    
    foreach ($this->getAttributeModifierFields() as $field) {
      $sum += abs($this->{$field} ?? 0);
    }
    
    return $sum;
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
    $absoluteSum = $this->getAbsoluteModifiersSum();
    $totalSum = $this->getTotalModifiersSum();
    $fieldValues = $this->getModifierValues();

    // Validate max modifiable attributes count
    if (isset($options['max_modifiable_attributes']) && $modifiedCount > $options['max_modifiable_attributes']) {
      return false;
    }

    // Validate absolute value sum
    if (isset($options['max_absolute_sum']) && $absoluteSum > $options['max_absolute_sum']) {
      return false;
    }

    // Validate total sum
    if (isset($options['max_total_sum']) && abs($totalSum) > $options['max_total_sum']) {
      return false;
    }

    // Validate per attribute limits
    if (isset($options['attribute_limits'])) {
      foreach ($fieldValues as $field => $value) {
        $attributeName = str_replace('_modifier', '', $field);
        if (isset($options['attribute_limits'][$attributeName])) {
          $limit = $options['attribute_limits'][$attributeName];
          
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
    }

    // Check for custom validation if defined
    if (isset($options['custom_validation']) && is_callable($options['custom_validation'])) {
      return $options['custom_validation']($this, $fieldValues);
    }

    return true;
  }
}