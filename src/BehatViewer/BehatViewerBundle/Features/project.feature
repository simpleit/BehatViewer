@config
Feature: Project

    @reset @javascript
    Scenario: First project
        Given I am a logged in user
          And I am on the homepage
         Then I should see an alert message with title "No project configured" and text "Before using Behat Viewer, you should configure your project."

        Given I fill in "Project name" with "Foo Bar"
          And I fill in "Identifier" with "foo-bar"
          And I press "Save changes"
         Then I should see "Settings were successfully saved"
          But I should not see "No project configured"
          And I should not see "Before using Behat Viewer, you should configure your project."
