@api
Feature: All pages

    @reset
    Scenario Outline: Anonymous user
        Given I am on "<url>"
         Then the response status code should be <status>

          Examples:
            | url                       | status |
            | /api                      | 404    |
            | /api/build                | 403    |
            | /api/analyze              | 403    |
            | /api/github               | 403    |

    @reset @fixture:user.sql
    Scenario Outline: Authenticated user
      Given I set basic authentication with "user" and "api_key"
       When I am on "<url>"
       Then the response status code should be <status>

          Examples:
            | url                       | status |
            | /api                      | 404    |
            | /api/build                | 404    |
            | /api/analyze              | 404    |
            | /api/github               | 404    |