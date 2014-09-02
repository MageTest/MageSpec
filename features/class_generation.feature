Feature: Magento classes are generated from specs
  So that I can practice SpecBDD with Magento
  As a developer
  I want Magespec to generate Magento classes from specs

  Scenario: Block class is generated from a spec
    Given Magespec has a standard configuration
    And there is a "block" spec
    When Magespec runs the spec
    Then a block class should be generated

  Scenario: Controller class is generated from a spec
    Given Magespec has a standard configuration
    And there is a "controller" spec
    When Magespec runs the spec
    Then a controller class should be generated

  Scenario: Helper class is generated from a spec
    Given Magespec has a standard configuration
    And there is a "helper" spec
    When Magespec runs the spec
    Then a helper class should be generated

  Scenario: Model class is generated from a spec
    Given Magespec has a standard configuration
    And there is a "model" spec
    When Magespec runs the spec
    Then a model class should be generated

  Scenario: Resource model class is generated from a spec
    Given Magespec has a standard configuration
    And there is a "resource model" spec
    When Magespec runs the spec
    Then a resource model class should be generated