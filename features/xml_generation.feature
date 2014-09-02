Feature: Module XML files are generated
  So that developing Magento modules is easier
  As a developer
  I want Magespec to generate XML files for my generated objects

  Scenario: Module XML is generated
    Given Magespec has a standard configuration
    And there is a spec for a module that does not yet exist
    When Magespec runs the spec
    Then the module XML file should be generated

  Scenario: Config XML is created
    Given Magespec has a standard configuration
    And there is a spec for a module that does not yet exist
    When Magespec runs the spec
    Then the config XML file should be generated