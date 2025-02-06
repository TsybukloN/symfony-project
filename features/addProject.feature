Feature: Add Project
  Scenario: Visiting the add project page
    Given I am logged in as "admin"
    When I am on "/projects/add"
    Then I should see "Add New Project"
