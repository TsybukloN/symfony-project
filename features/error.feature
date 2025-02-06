Feature: Error Page
  Scenario: Visiting a non-existent page
    Given I am on "/non-existent-page"
    Then I should see "Error 404."
