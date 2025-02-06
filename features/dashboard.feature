Feature: Dashboard
  Scenario: Visiting the dashboard page
    Given I am logged in as "admin"
    When I am on "/projects"
    Then I should see "Dashboard"
