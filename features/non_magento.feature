Feature: Underlying PhpSpec still works correctly for non Magento objects
  So that I can improve Magespec
  As a developer
  I want to be able to use PhpSpec to develop Magespec

  Scenario: A simple spec is generated
    Given Magespec has a standard configuration
    When I describe a non Magento object
    Then the non Magento spec should be generated

  Scenario: A simple spec can run run
    Given Magespec has a standard configuration
    And there is a spec for a new non Magento object
    When Magespec runs the spec
    Then the non Magento object should be generated