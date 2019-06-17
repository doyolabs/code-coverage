Feature: Local

  Scenario: Say foo bar
    Given I send a "GET" request to "/local.php"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node foo should exist

