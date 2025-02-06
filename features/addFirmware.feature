Feature: Add Firmware
  Scenario: Visiting the add firmware page
    Given I am logged in as "admin"
    When I am on "/firmwares/add?projectId=1"
    Then I should see "Add Firmware"
