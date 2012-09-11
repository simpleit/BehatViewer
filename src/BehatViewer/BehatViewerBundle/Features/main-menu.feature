@navigation
Feature: Main menu

    @reset
    Scenario: Main menu
        Given I am on the homepage
         Then I should see "Behat Viewer"
          And I should see "Help"

    @reset @fixture:single-project.sql
    Scenario Outline: Main menu links
        Given I am a logged in user
          And I am on the homepage
          And I follow "Details"
          And I follow "<link>"
         Then I should be on "<url>"

        Examples:
            | link        | url                       |
            | Features    | /user/foo-bar             |
            | History     | /user/foo-bar/history     |
            | Stats       | /user/foo-bar/stats       |
            | Definitions | /user/foo-bar/definitions |