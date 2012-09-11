@build @feature @project
Feature: Homepage

    @reset
    Scenario: Without data
        Given I am on homepage
         Then I should be on "/login"
          And I should see "Login"

    @reset @fixture:single-project.sql @fixture:second-project.sql @fixture:single-build.sql
    Scenario: Anonymous user
        Given I am on the homepage
         Then I should see "user/Foo Bar (50%)"

    @reset
    Scenario: First project
        Given I am a logged in user
          And I am on the homepage
         Then I should see an alert message with title "No project configured" and text "Before using Behat Viewer, you should configure your project."

    @reset @fixture:single-project.sql
    Scenario: Add project button
        Given I am on the homepage
         Then I should not see "Add project"

        Given I am a logged in user
          And I am on the homepage
         Then I should see "Add project"

        Given I follow "Add project"
         Then I should see "New project"

    @reset @fixture:single-project.sql @fixture:second-project.sql
    Scenario: Single project with build
        Given I am a logged in user
          And I am on the homepage
         Then I should see "user/Foo Bar"
          And I should see a button with text "Details »"
          And I should see a "trash" iconic button

    @reset @fixture:single-project.sql @fixture:single-build.sql @fixture:second-project.sql
    Scenario: Single project with one build
        Given I am a logged in user
          And I am on the homepage
         Then I should see "user/Foo Bar (50%)"
