@build
Feature: Last build notification

    @reset @fixture:single-project.sql @fixture:single-build.sql @fixture:second-build.sql
    Scenario: Last build notification
        Given I am a logged in user
          And I am on the homepage
          And I follow "Details"
         Then I should not see "Last build"

        Given I go to "/user/foo-bar/1"
         Then I should see "Last build"
