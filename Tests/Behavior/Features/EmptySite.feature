@browser
Feature: Purge data on @fixtures

  @fixtures @remote @clearcodecache
  Scenario: Run @fixtures and access the Website
    Given I go to "/"
    Then I should see "Missing Homepage"
