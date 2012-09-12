@user
Feature: Profile

    @reset
    Scenario: Edit profile
        Given I am a logged in user
          And I am on the homepage
          And I follow "Logged in as user"
          And I follow "Profile"
         Then I should be on "/profile"
          And I should see "Your profile : user"
          And I should see "API Token"
          And I should see "Change password"
          And I should see a button with text "Click here to change your password »"

        Given I fill in "Username" with "viewer"
          And I press "Save changes"
         Then I should be on "/profile"
          And I should see "Your profile : viewer"
          And I should see "Logged in as viewer"

        Given I fill in "Username" with "behat"
          And I fill in "E-mail" with "behat@viewer.com"
          And I press "Save changes"
         Then I should be on "/profile"
          And The value of the "E-mail" field should be "behat@viewer.com"