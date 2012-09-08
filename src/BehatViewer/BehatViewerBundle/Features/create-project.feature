@project @user
Feature: Create project

  @reset @javascript @fixture:single-project.sql
  Scenario: Create a new project
    Given I am a logged in user
      And I am on the homepage
      And I follow "Logged in as user"
      And I follow "Projects"
      And I follow "Add project"
     Then I should see "New project"

    Given I fill in "Project name" with "Bar Foo"
      And I fill in "Identifier" with "bar-foo"
      And I press "Save changes"
     Then I should see "Settings were successfully saved."

  @reset @javascript @fixture:single-project.sql
  Scenario: Project with duplicate name and identifier
    Given I am a logged in user
      And I am on the homepage
      And I follow "Logged in as user"
      And I follow "Projects"
      And I follow "Add project"
     Then I should see "New project"

    Given I fill in "Project name" with "Foo Bar"
      And I fill in "Identifier" with "foo-baz"
      And I press "Save changes"
     Then I should see "You already own a project with the same name"

    Given I fill in "Identifier" with "foo-bar"
      And I press "Save changes"
     Then I should see "You already own a project with the same identifier"

    Given I fill in "Project name" with "Foo Baz"
      And I fill in "Identifier" with "foo-baz"
      And I press "Save changes"
     Then I should see "Project configuration"
     Then I should see "Settings were successfully saved."