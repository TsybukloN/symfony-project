Feature: Edit Project
  Scenario: Visiting the edit project page
    Given I am logged in as "admin"
    When I am on "/projects/edit/1"
    Then I should see "Edit Project"
