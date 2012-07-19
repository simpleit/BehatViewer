Feature: Create project

  @reset @javascript @fixture:single-project.sql
  Scenario: New project
    Given I am a logged in user
      And I am on the homepage
      And I follow "Logged in as behat"
      And I follow "Projects"
     Then I should see a "table" element
      And the columns schema of the "table" table should match:
        | Columns Title |
        | #             |
        | Name          |
        | Identifier    |
        | Root path     |
        | Output path   |
        | Action        |
       And the data in the 1st row of the "table" table should match:
        | # | Name    | Identifier      | Root path | Output path | Action   |
        | 1 | Foo Bar | foo-bar         | /foo/bar  | /foo/bar    | ViewEdit |

  Scenario: Project with duplicate name and identifier