@build
Feature: Last build notification

    @reset @fixture:single-project.sql @fixture:single-build.sql
    Scenario: Last build notification
        Given I am a logged in user
          And I am on the homepage
          And I follow "Details"
         Then I should not see "Last build"

        Given I load the "second-build.sql" fixture
          And I reload the page
         Then I should see "Last build"
