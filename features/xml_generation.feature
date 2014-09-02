Feature: Module XML files are generated
  So that developing Magento modules is easier
  As a developer
  I want Magespec to generate XML files for my generated objects

  Scenario: Module XML is generated
    Given Magespec has a standard configuration
    And there is a new module
    And there is a "model" spec
    When Magespec runs the spec
    Then the module XML file should be generated

  Scenario: Config XML is created
    Given Magespec has a standard configuration
    And there is a new module
    And there is a "model" spec
    When Magespec runs the spec
    Then the config XML file should be generated

  Scenario: Config XML contains a model element
    Given Magespec has a standard configuration
    And there is a new module
    And there is a "model" spec
    When Magespec runs the spec
    Then the config XML file should contain a "model" element

  Scenario: Config XML contains a block element
    Given Magespec has a standard configuration
    And there is a new module
    And there is a "block" spec
    When Magespec runs the spec
    Then the config XML file should contain a "block" element

  Scenario: Config XML contains a helper element
    Given Magespec has a standard configuration
    And there is a new module
    And there is a "helper" spec
    When Magespec runs the spec
    Then the config XML file should contain a "helper" element

  Scenario: Config XML contains a helper element
    Given Magespec has a standard configuration
    And there is a new module
    And there is a "controller" spec
    When Magespec runs the spec
    Then the config XML file should contain a "controller" element

  Scenario: Config XML contains a resource model element
    Given Magespec has a standard configuration
    And there is a new module
    And there is a "resource model" spec
    When Magespec runs the spec
    Then the config XML file should contain a "resource model" element