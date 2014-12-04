Feature: specs are generated for Magento objects
  So that I can practice SpecBDD with Magento
  As a developer
  I want Magespec to generate specs for various Magento object types

  Scenario: Block specs are generated
    Given Magespec has a standard configuration
    And my module namespace is "Behat_Test"
    When I describe a "block"
    Then a correctly namespaced block spec should be generated

  Scenario: Controller specs are generated
    Given Magespec has a standard configuration
    And my module namespace is "Behat_Test"
    When I describe a "controller"
    Then a correctly namespaced controller spec should be generated

  Scenario: Helper specs are generated
    Given Magespec has a standard configuration
    And my module namespace is "Behat_Test"
    When I describe a "helper"
    Then a correctly namespaced helper spec should be generated

  Scenario: Model specs are generated
    Given Magespec has a standard configuration
    And my module namespace is "Behat_Test"
    When I describe a "model"
    Then a correctly namespaced model spec should be generated

  Scenario: Resource model specs are generated
    Given Magespec has a standard configuration
    And my module namespace is "Behat_Test"
    When I describe a "resource_model"
    Then a correctly namespaced resource model spec should be generated