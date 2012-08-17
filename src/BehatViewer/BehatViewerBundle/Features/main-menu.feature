@navigation
Feature: Main menu

    @reset
    Scenario: Main menu links without data
        Given I am on the homepage
         Then I should see "Behat Viewer"
          And I should see "Help"

        Given I follow "Help"
        Then  I should be on "/help"

    @reset @fixture:single-project.sql
    Scenario Outline: Main menu links with data
        Given I am a logged in user
          And I am on the homepage
          And I follow "<link>"
         Then I should be on "<url>"

        Examples:
            | link        | url                       |
            | Features    | /user/foo-bar             |
            | History     | /user/foo-bar/history     |
            | Stats       | /user/foo-bar/stats       |
            | Definitions | /user/foo-bar/definitions |