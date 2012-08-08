@navigation
Feature: All pages

    @reset
    Scenario Outline: Anonymous user and no data
        Given am on "<url>"
         Then the response status code should be <status>
          And I should see "Login"

          Examples:
            | url                       | status |
            | /                         | 200    |
            | /login                    | 200    |
            | /profile                  | 200    |
            | /logout                   | 200    |
            | /config                   | 200    |
            | /user/project             | 200    |
            | /user/project/history     | 200    |
            | /user/project/stats       | 200    |
            | /user/project/definitions | 200    |

    @reset @fixture:single-project.sql @fixture:single-build.sql
    Scenario Outline: Anonymous user with data
        Given I am on "<url>"
         Then the response status code should be <status>
          And I should see "Login"

          Examples:
            | url                       | status |
            | /                         | 200    |
            | /login                    | 200    |
            | /profile                  | 200    |
            | /logout                   | 200    |
            | /config                   | 200    |
            | /user/project             | 200    |
            | /user/project/history     | 200    |
            | /user/project/stats       | 200    |
            | /user/project/definitions | 200    |

    @reset
    Scenario Outline: Logged in user without data
        Given I am a logged in user
          And I am on "<url>"
         Then the response status code should be <status>
          And I should see "No project configured"
          And I should see "Before using Behat Viewer, you should configure your project."

          Examples:
            | url                       | status |
            | /                         | 200    |
            | /user/project             | 200    |
            | /user/project/history     | 200    |
            | /user/project/stats       | 200    |
            | /user/project/definitions | 200    |