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
            | /admin/config             | 200    |

    @reset
    Scenario Outline: Accessing not existing resources
        Given am on "<url>"
         Then the response status code should be <status>

          Examples:
            | url                       | status |
            | /user                     | 404    |
            | /user/project             | 404    |
            | /user/project/history     | 404    |
            | /user/project/stats       | 404    |
            | /user/project/definitions | 404    |
            | /user/project/edit        | 404    |

    @reset @fixture:user.sql @fixture:single-project.sql @fixture:single-build.sql
    Scenario Outline: Anonymous user with data
        Given I am on "<url>"
         Then the response status code should be <status>
          And I should see "Login"

          Examples:
            | url                       | status |
            | /login                    | 200    |
            | /profile                  | 200    |
            | /admin/config             | 200    |
            | /projects                 | 200    |
            | /password                 | 200    |
            | /project/create           | 200    |
            | /user                     | 200    |
            | /user/foo-bar             | 200    |
            | /user/foo-bar/edit        | 200    |
            | /user/foo-bar/delete      | 200    |

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
            | /project/create           | 200    |

  @reset @fixture:single-project.sql
  Scenario Outline: Logged in user with data
      Given I am a logged in user
        And I am on "<url>"
       Then the response status code should be <status>
        And I should not see "No project configured"
        And I should not see "Before using Behat Viewer, you should configure your project."

      Examples:
        | url                            | status |
        | /                              | 200    |
        | /user/doesnotexist             | 404    |
        | /user/foo-bar                  | 200    |
        | /user/doesnotexist/history     | 404    |
        | /user/foo-bar/history          | 200    |
        | /user/doesnotexist/stats       | 404    |
        | /user/foo-bar/stats            | 200    |
        | /user/doesnotexist/definitions | 404    |
        | /user/foo-bar/definitions      | 200    |