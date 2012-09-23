@build @feature
Feature: Feature page

    @reset @javascript @fixture:single-project.sql @fixture:single-build.sql
    Scenario: Navigation in features details
        Given I am a logged in user
          And I am on the homepage
          And I follow "Details"
         Then I should see "Features for Foo Bar"

        Given I follow "Details"
         Then I should see "Passed (#1 Built 43 years ago on 1970-01-01 00:00:00)"
          And I should see:
            """
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc posuere mollis quam sed rhoncus. Lorem ipsum dolor sit amet, consectetur.
            """
          And I should see "Passed 3 step(s) / Passed: 3/3 (100%)"
          And I should see 3 passed steps

        Given I follow "Failed"
         Then I should see "Failed (#1 Built 43 years ago on 1970-01-01 00:00:00)"
          And I should see:
            """
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc posuere mollis quam sed rhoncus. Lorem ipsum dolor sit amet, consectetur.
            """
          And I should see "Failed 5 step(s) / Passed: 4/5 (80%) / Failed: 1/5 (20%)"
          And I should see 4 passed steps
          And I should see 1 failed step

    @reset @javascript @fixture:single-project.sql @fixture:all-step-statuses.sql
    Scenario: All step statuses
        Given I am a logged in user
          And I am on the homepage
          And I follow "Details"
         Then I should see "Features for Foo Bar"

        Given I follow "Details"
         Then I should see "All statuses (#3 Built 43 years ago on 1970-01-01 00:00:00)"
          And I should see "Scenario 5 step(s) / Passed: 1/5 (20%) / Failed: 1/5 (20%) / Skipped: 1/5 (20%) / Pending: 1/5 (20%) / Undefined: 1/5 (20%)"
          And I should see 1 passed step
          And I should see 1 failed step
          And I should see 1 skipped step
          And I should see 1 pending step
          And I should see 1 undefined step
