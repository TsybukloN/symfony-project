Feature: Profile
  Scenario: Visiting the profile page
    Given I am logged in as "admin"
    When I am on "/profile"
    Then I should see "Profile"
