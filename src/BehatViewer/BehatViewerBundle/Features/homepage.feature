@build @feature @project
Feature: Homepage

    @reset @fixture:single-project.sql
    Scenario: Redirect when single project
        Given I am a logged in user
          And I am on the homepage
         Then I should see "Features for Foo Bar"

    @reset @fixture:single-project.sql @fixture:second-project.sql
    Scenario: Single project with build
        Given I am a logged in user
          And I am on the homepage
         Then I should see "user/Foo Bar"
          And I should see "Details »"

    @reset @fixture:single-project.sql @fixture:single-build.sql @fixture:second-project.sql
    Scenario: Single project with one build
        Given I am a logged in user
          And I am on the homepage
         Then I should see "user/Foo Bar (50%)"
