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
        clover: build/clover.xml
        crap4j: build/logs/crap4j.xml
        html: build/html
        php: build/cov/php.cov
        text: console
        xml: build/xml
      """
    Then service "<processor>" should loaded

    Examples:
      | processor          |
      | reports.clover     |
      | reports.crap4j     |
      | reports.html       |
      | reports.php        |
      | reports.text       |
      | reports.xml        |
