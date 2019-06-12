Feature: Configuration

  Scenario Outline: Core services should loaded
    Given I have load container
    Then service <service> should loaded

    Examples:
      | service                      |
      | report                       |
      | runtime                      |
      | coverage.filter              |
      | coverage.driver              |
      | processor                    |
      | coverage                     |
      | report                       |


  Scenario Outline: Report processors should loaded
    Given I have load container with:
      """
      reports:
        php: build/cov/php.cov
        html: build/html
      """
    Then service "<processor>" should loaded

    Examples:
      | processor                       |
      | report.processors.html          |
      | report.processors.php           |
      | report.processors.clover        |
