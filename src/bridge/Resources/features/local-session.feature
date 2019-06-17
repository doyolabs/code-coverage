Feature: Local Session
  Background:
    Given I configure behat with:
      """
      default:
          suites:
              default:
                  paths:
                      - 'features'
                  contexts:
                      - Behat\MinkExtension\Context\MinkContext
                      - behatch:context:rest
                      - behatch:context:json
          extensions:
              Behat\MinkExtension:
                  base_url: http://localhost:8000
                  sessions:
                      default:
                          goutte: ~
              Behatch\Extension: ~
              Doyo\Behat\CodeCoverage\Extension:
                  env: dev
                  debug: true
                  filter:
                      - src
                  sessions:
                      local: ~
                  reports:
                      php: build/local.cov
      """

  @coverage
  Scenario: Run code coverage with local session enabled
    When I run behat
    Then I should see console output "1 scenario (1 passed)"
    And I should see console output "4 steps (4 passed)"
    When I read coverage report "build/local.cov"
    Then file "src/Foo.php" line 11 should be covered

