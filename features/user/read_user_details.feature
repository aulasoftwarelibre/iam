@user @api
Feature: Show user details
    In order to get all information about an user
    As a sysadmin
    I want to see user details

    Scenario: Get details from an user
        Given an account with username "johndoe"
        When I check the user details
        Then I should see than the username is "johndoe"
